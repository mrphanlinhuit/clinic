<?php

class ApiMessageController extends BaseApiController {

	/**
	 * [getById description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getById($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		$message = Message::find($id);
		if ($message||($message->sender == $currentUser->id)||$currentUser->isAdmin()){
			if ($message->receiver == $currentUser->id){
				$message->read_at = time();
				$message->save();
			}
			
			$message->name = User::find($message->sender)->fullName();
			$resp = RestResponseFactory::ok($message->toArray());
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}
	/**
	 * [create description]
	 * @return [type]
	 */
	public function create()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->id){
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'body'     => 'required|min:2',
					'topic'    => 'required|min:2',
					'receiver' => 'required|numeric',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$message           = new Message();
			$message->body     = $request["body"];
			$message->topic    = $request["topic"];
			$message->receiver = $request["receiver"];
			$message->sender   = $currentUser->id;
			$message->save();

			$resp = RestResponseFactory::ok($message->toArray(), "Message is create successful");
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

			$message = Message::find($id);
			if (!$rule) {
				$message = RestResponseFactory::ok(null, "Message not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'body'     => 'required|min:2',
					'topic'    => 'required|min:2',
					'receiver' => 'required|numeric',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$message->body     = $request["body"];
			$message->topic    = $request["topic"];
			$message->receiver = $request["receiver"];
			$message->sender   = $currentUser->id;
			$message->save();

			$resp = RestResponseFactory::ok($rule->toArray(), "Message is update successful");
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
		//if ($currentUser->isAdmin()){
			// Code
			$rule = Message::find($id);
			if (!$rule) {
				$resp = RestResponseFactory::ok(null, "Message not found.");
				return Response::json($resp);
			}

			$rule->delete();

			$resp = RestResponseFactory::ok(null, "Message is delete successful");
		// }
		// else {
		// 	$resp = RestResponseFactory::ok(null, "User not permission.");
		// }
		return Response::json($resp);
	}

	/**
	 *  @param  page    optional    page number
	 *  @param  limit   optional    page limit
	 *
	 *  @desc Search users
	 **/

	/**
	 * [search description]
	 * @return [type]
	 */
	public function search()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int)Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort  = Input::get('sort') ? Input::get('sort') : 'id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("users_message")
			->select(DB::raw("users_message.*, CONCAT_WS(' ', users_meta.first_name, users_meta.last_name) AS name"))
			->leftJoin("users_meta", "users_meta.user_id", "=", "users_message.sender");

		if (!$currentUser->isAdmin()){
			$query->where('users_message.receiver', $currentUser->id);
		}
		if ($receiver = Input::get('receiver')) {
			$query->where('users_message.receiver', $receiver);
		}
		if ($sender = Input::get('sender')) {
			$query->where('users_message.sender', $sender);
		}
		
		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', users_message.body, users_meta.first_name, users_meta.last_name) like ?", array('%'.$q.'%'));
		}

		$query->groupBy("users_message.id");
		$query->orderBy($sort, $order);

		$messages = $query->paginate($limit);
		if ($messages) {
			$resp = RestResponseFactory::ok($messages->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Message(s) not found.");
		}
		return Response::json($resp);
	}
}