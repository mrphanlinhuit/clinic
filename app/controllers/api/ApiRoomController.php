<?php

class ApiRoomController extends BaseApiController {

	/**
	 *  @desc Room
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all = Room::all();
			$rooms = array();
			foreach ($all as $a) $rooms[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($rooms);
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
					'name'   => 'required|unique:room',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$room              = new Room();
			$room->name        = $request["name"];
			$room->description = isset($request["description"])?$request["description"]:"";
			$room->status      = isset($request["status"])?$request["status"]:false;
			$room->save();

			$resp = RestResponseFactory::ok($room->toArray(), "Room is create successful");
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

			$room = Room::find($id);
			if (!$room) {
				$resp = RestResponseFactory::ok(null, "Room not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'   => 'required|unique:room,name,' . $room->id,
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$room->name        = $request["name"];
			$room->description = isset($request["description"])?$request["description"]:"";
			$room->status      = isset($request["status"])?$request["status"]:false;
			$room->save();

			$resp = RestResponseFactory::ok($room->toArray(), "Room is update successful");
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
			$room = Room::find($id);
			if (!$room) {
				$resp = RestResponseFactory::ok(null, "Room not found.");
				return Response::json($resp);
			}

			$room->delete();

			$resp = RestResponseFactory::ok(null, "Room is delete successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 * [search description]
	 * @return [type] [description]
	 */
	public function search()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int)Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort  = Input::get('sort') ? Input::get('sort') : 'room.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("room")
			->select(DB::raw("room.id, room.name, room.description, room.status"));

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `room`.`name`) like ?", array('%'.$q.'%'));
		}

		if ($status = Input::get('status')) {
			$query->where('room.status', $status);
		}

		$query->groupBy("room.id");
		$query->orderBy($sort, $order);

		$room = $query->paginate($limit);
		if ($room) {
			$resp = RestResponseFactory::ok($room->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Room(s) not found.");
		}
		return Response::json($resp);
	}


	/**
	 * [getById description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getById($id)
	{
		$room = Room::find($id);
		if ($room) {
			$resp = RestResponseFactory::ok($room->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Room(s) not found.");
		}
		return Response::json($resp);
	}
}