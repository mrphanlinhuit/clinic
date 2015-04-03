<?php

class ApiPathologyController extends BaseApiController {

	/**
	 *  @desc Pathology
	 **/
	public function all()
	{
		$authToken   = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all   = Pathology::all();
			$pathologies = array();
			foreach ($all as $a) $pathologies[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($pathologies);
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
				$request,
				array(
					'name' => 'required|unique:pathology'
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$pathology       = new Pathology();
			$pathology->name = $request["name"];
			$pathology->prescription = $request["prescription"];
			$pathology->save();

			$resp = RestResponseFactory::ok($pathology->toArray(), "Pathology is create successful");
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

			$pathology = Pathology::find($id);
			if (!$pathology) {
				$resp = RestResponseFactory::ok(null, "Pathology not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name' => 'required|unique:pathology,name,' . $pathology->id
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$pathology->name = $request["name"];
			$pathology->save();

			$resp = RestResponseFactory::ok($pathology->toArray(), "Pathology is update successful");
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
			$pathology = Pathology::find($id);
			if (!$pathology) {
				$resp = RestResponseFactory::ok(null, "Pathology not found.");
				return Response::json($resp);
			}

			$pathology->delete();

			$resp = RestResponseFactory::ok(null, "Pathology is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'pathology.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("pathology")
			->select(DB::raw("pathology.id, pathology.name"));

		if ($q) {
			$query->whereRaw("pathology.name LIKE ?", array('%'.$q.'%'));
		}

		if ($status = Input::get('status')) {
			$query->where('pathology.status', $status);
		}

		$query->groupBy("pathology.id");
		$query->orderBy($sort, $order);

		$rules = $query->paginate($limit);
		if ($rules) {
			$resp = RestResponseFactory::ok($rules->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Pathology(s) not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Get user by id
	 **/
	public function getById($id)
	{
		//$authToken = App::make('authToken');
		//if ($authToken->user->isAdmin()) {
			$pathology = Pathology::find($id);
		//}
		if ($pathology) {
			$resp = RestResponseFactory::ok($pathology->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Pathology(s) not found.");
		}
		return Response::json($resp);
	}
}