<?php

class ApiGroupController extends BaseApiController {

	/**
	 *  @desc Group
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all = Group::all();
			$groups = array();
			foreach ($all as $a) $groups[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($groups);
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
					'name'    => 'required|unique:users_group'
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$group              = new Group();
			$group->name        = $request["name"];
			$group->description = $request["description"];
			$group->save();

			$resp = RestResponseFactory::ok($group->toArray(), "Group is create successful");
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

			$group = Group::find($id);
			if (!$group) {
				$resp = RestResponseFactory::ok(null, "Group not found.");
				return Response::json($resp);
			}

			$errors = array();
			if(!isset($request["name"])&&isset($request["status"])){
				$group->status = $request["status"];
				$group->save();
			} else {
				$validator = Validator::make(
					array(
						$request,
						'name'    => 'required|name|unique:users_group'
					)
				);
				if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
				if (count($errors) > 0) {
					$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
					return Response::json($resp);
				}
				$group->name        = $request["name"];
				$group->description = $request["description"];
				$group->save();
			}

			$resp = RestResponseFactory::ok($group->toArray(), "Group is update successful");
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
			$group = Group::find($id);
			if (!$group) {
				$resp = RestResponseFactory::ok(null, "Group not found.");
				return Response::json($resp);
			}

			$group->delete();

			$resp = RestResponseFactory::ok(null, "Group is delete successful");
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
		$sort = Input::get('sort') ? Input::get('sort') : 'users_group.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("users_group")
			->select(DB::raw("users_group.id, users_group.name, users_group.description, users_group.status, users_group.created_at, users_group.updated_at"));

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `users_group`.`name`) like ?", array('%'.$q.'%'));
		}

		if ($status = Input::get('status')) {
			$query->where('users_group.status', $status);
		}

		$query->groupBy("users_group.id");
		$query->orderBy($sort, $order);

		$groups = $query->paginate($limit);
		if ($groups) {
			$resp = RestResponseFactory::ok($groups->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Group(s) not found.");
		}
		return Response::json($resp);
	}
}