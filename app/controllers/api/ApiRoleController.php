<?php

class ApiRoleController extends BaseApiController {

	/**
	 *  @desc Role
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$roles = Role::all()->toArray();
			//$roles = array();
			//foreach ($all as $a) $roles[] = array("key" => $a->id, "label" => $a->description);
			$resp = RestResponseFactory::ok($roles);
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public function create()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$errors = array();
			$validator = Validator::make(
				array(
					$request,
					'name'    => 'required|unique:users_role'
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$role              = new Role();
			$role->name        = $request["name"];
			$role->description = $request["description"];
			$role->save();

			$resp = RestResponseFactory::ok($role->toArray(), "Role is create successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public function update($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$role = Role::find($id);
			if (!$role) {
				$resp = RestResponseFactory::ok(null, "Role not found.");
				return Response::json($resp);
			}

			$errors = array();
			if(!isset($request["name"])&&isset($request["status"])){
				$role->status = $request["status"];
				$role->save();
			} else {
				$validator = Validator::make(
					array(
						$request,
						'name'    => 'required|name|unique:users_role'
					)
				);
				if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
				if (count($errors) > 0) {
					$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
					return Response::json($resp);
				}
				$role->name        = $request["name"];
				$role->description = $request["description"];
				$role->save();
			}

			$resp = RestResponseFactory::ok($role->toArray(), "Role is update successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public function delete($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			// Code
			$role = Role::find($id);
			if (!$role) {
				$resp = RestResponseFactory::ok(null, "Role not found.");
				return Response::json($resp);
			}

			$role->delete();

			$resp = RestResponseFactory::ok(null, "Role is delete successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 *  @param  page    optional    page number
	 *  @param  limit   optional    page limit
	 *
	 *  @desc Search users
	 **/
	public function search()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int)Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort = Input::get('sort') ? Input::get('sort') : 'users_role.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("users_role")
			->select(DB::raw("users_role.id, users_role.name, users_role.description, users_role.status, users_role.created_at, users_role.updated_at"));

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `users_role`.`name`) like ?", array('%'.$q.'%'));
		}

		if ($status = Input::get('status')) {
			$query->where('users_role.status', $status);
		}

		$query->groupBy("users_role.id");
		$query->orderBy($sort, $order);

		$roles = $query->paginate($limit);
		if ($roles) {
			$resp = RestResponseFactory::ok($roles->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Role(s) not found.");
		}
		return Response::json($resp);
	}
}