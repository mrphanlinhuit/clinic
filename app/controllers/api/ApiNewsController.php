<?php

class ApiNewsController extends BaseController {


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
					'title'   	  => 'required',
					'description' => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$news              = new News();
			$news->title       = $request["title"];
			$news->description = isset($request["description"])?$request["description"]:"";
			$news->image_url = isset($request["image_url"])?$request["image_url"]:"";
			$news->save();

			$resp = RestResponseFactory::ok($news->toArray(), "News is create successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$news = News::find($id);
			if (!$news) {
				$resp = RestResponseFactory::ok(null, "News not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'title'   	  => 'required',
					'description' => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$news->title       = $request["title"];
			$news->description = isset($request["description"])?$request["description"]:"";
			$news->image_url = isset($request["image_url"])?$request["image_url"]:"";
			$news->save();

			$resp = RestResponseFactory::ok($news->toArray(), "News is update successful");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 * Delete the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			$news = News::find($id);
			if (!$news) {
				$resp = RestResponseFactory::ok(null, "news not found.");
				return Response::json($resp);
			}

			$news->delete();

			$resp = RestResponseFactory::ok(null, "News is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'news.id';
		$order = Input::get('order') ? Input::get('order') : 'desc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("news")
			->select(DB::raw("news.*"));

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `news`.`title`) like ?", array('%'.$q.'%'));
		}

		$query->groupBy("news.id");
		$query->orderBy($sort, $order);

		$news = $query->paginate($limit);
		if ($news) {
			$resp = RestResponseFactory::ok($news->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "News(s) not found.");
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
		$news = News::find($id);
		if ($news) {
			$resp = RestResponseFactory::ok($news->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "News(s) not found.");
		}
		return Response::json($resp);
	}


}
