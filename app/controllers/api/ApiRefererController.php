<?php

class ApiRefererController extends BaseApiController {

	/**
	 *  @desc Referer
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all = Referer::all();
			$referers = array();
			foreach ($all as $a) $referers[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($referers);
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
					'name'   => 'required|unique:referer',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$referer         = new Referer();
			$referer->name   = $request["name"];
			$referer->status = isset($request["status"])?$request["status"]:false;
			$referer->save();

			$resp = RestResponseFactory::ok($referer->toArray(), "Referer is create successful");
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

			$referer = Referer::find($id);
			if (!$referer) {
				$resp = RestResponseFactory::ok(null, "Referer not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'   => 'required|unique:referer,name,' . $referer->id,
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$referer->name   = $request["name"];
			$referer->status = (isset($request["status"]))?$request["status"]:false;
			$referer->save();

			$resp = RestResponseFactory::ok($referer->toArray(), "Referer is update successful");
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
			$referer = Referer::find($id);
			if (!$referer) {
				$resp = RestResponseFactory::ok(null, "Referer not found.");
				return Response::json($resp);
			}

			$referer->delete();

			$resp = RestResponseFactory::ok(null, "Referer is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'referer.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("referer")
			->select(DB::raw("referer.id, referer.name, referer.status"));

		if ($q) {
			$query->whereRaw("referer.name LIKE ?", array('%'.$q.'%'));
		}

		if ($status = Input::get('status')) {
			$query->where('referer.status', $status);
		}

		$query->groupBy("referer.id");
		$query->orderBy($sort, $order);

		$referer = $query->paginate($limit);
		if ($referer) {
			$resp = RestResponseFactory::ok($referer->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Referer(s) not found.");
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
			$referer = Referer::find($id);
		//}
		if ($referer) {
			$resp = RestResponseFactory::ok($referer->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Referer(s) not found.");
		}
		return Response::json($resp);
	}
}