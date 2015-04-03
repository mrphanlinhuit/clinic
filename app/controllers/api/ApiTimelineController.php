<?php

class ApiTimelineController extends BaseApiController {

	/**
	 *  @desc Timeline
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all = Timeline::all();
			$timeline = array();
			foreach ($all as $a) $timeline[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($timeline);
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
					'user_id' => 'required|unique:timeline,user_id',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$timeline         = new Timeline();
			$timeline->user_id = $request["user_id"];
			$timeline->note    = (isset($request["note"]))?$request["note"]:$timeline->note;
			$timeline->save();

			$resp = RestResponseFactory::ok($timeline->toArray(), "Timeline is create successful");
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

			$timeline = Timeline::find($id);
			if (!$timeline) {
				$resp = RestResponseFactory::ok(null, "Timeline not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'user_id'   => 'required|unique:timeline,user_id,' . $timeline->id,
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$timeline->user_id = $request["user_id"];
			$timeline->note    = (isset($request["note"]))?$request["note"]:$timeline->note;
			$timeline->save();

			$resp = RestResponseFactory::ok($timeline->toArray(), "Timeline is update successful");
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
			$timeline = Timeline::find($id);
			if (!$timeline) {
				$resp = RestResponseFactory::ok(null, "Timeline not found.");
				return Response::json($resp);
			}
			$timeline->delete();

			$resp = RestResponseFactory::ok(null, "Timeline is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'timeline.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("timeline")->select(DB::raw("timeline.*, CONCAT_WS(' ', PT.first_name, PT.last_name) AS patient_name, CONCAT_WS(' ', CR.first_name, CR.last_name) AS created_by_name"))
			->leftJoin("users_meta as PT", "timeline.user_id", "=", "PT.user_id")
			->leftJoin("users_meta as CR", "timeline.created_by", "=", "CR.user_id");
		if (!$currentUser->isAdmin()){
			$query->where('timeline.user_id', $currentUser->id); // only actives
		}

		if ($user_id = Input::get('user_id')) {
			$query->where('timeline.user_id', $user_id);
		}

		if ($q) {
			$query->whereRaw("timeline.note LIKE ?", array('%'.$q.'%'));
		}

		$query->groupBy("timeline.id");
		$query->orderBy($sort, $order);

		$timeline = $query->paginate($limit);
		if ($timeline) {
			$resp = RestResponseFactory::ok($timeline->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Timeline(s) not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Get user by id
	 **/
	public function getById($id)
	{
		$timeline = Timeline::find($id);
		if ($timeline) {
			$resp = RestResponseFactory::ok($timeline->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Timeline(s) not found.");
		}
		return Response::json($resp);
	}
}