<?php

class ApiTagsController extends BaseController {


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		
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
			$tags = Tags::find($id);
			if (!$tags) {
				$resp = RestResponseFactory::ok(null, "tags not found.");
				return Response::json($resp);
			}

			$tags->delete();

			$resp = RestResponseFactory::ok(null, "tags is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'tags.id';
		$order = Input::get('order') ? Input::get('order') : 'desc';
		$q     = Input::get('q') ? Input::get('q') : null;

		// stats query
		$query = DB::table("tags")->select(DB::raw("tags.*"));

		if ($q) {
			$query->whereRaw("tags.name like ?", array('%'.$q.'%'));
		}

		$query->groupBy("tags.id");
		$query->orderBy($sort, $order);

		$tags = $query->paginate($limit);
		if ($tags) {
			$resp = RestResponseFactory::ok($tags->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Tags(s) not found.");
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
		$tags = Tags::find($id);
		if ($tags) {
			$resp = RestResponseFactory::ok($tags->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Tags(s) not found.");
		}
		return Response::json($resp);
	}


}
