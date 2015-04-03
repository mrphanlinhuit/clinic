<?php

class ApiDiagnosticReviewController extends BaseApiController {

	/**
	 *  @desc DiagnosticReview
	 **/
	public function all()
	{
		$authToken   = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all   = DiagnosticReview::all();
			$reviews = array();
			foreach ($all as $a) $reviews[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($reviews);
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
					'description'   => 'required',
					'diagnostic_id' => 'integer',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$review                = new DiagnosticReview();
			$review->description   = isset($request["description"])?$request["description"]:"";
			$review->diagnostic_id = isset($request["diagnostic_id"])?$request["diagnostic_id"]:"";
			$review->created_by    = $currentUser->id;
			$review->updated_by    = $currentUser->id;
			$review->save();

			$review->updated_by_name = $review->created_by_name = $currentUser->fullName();

			//Timeline
			$diagnostic           = Diagnostic::find($review->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Diagnostic's review is create by " . $review->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($review->toArray(), "Review is create successful");
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

			$review = DiagnosticReview::find($id);
			if (!$review) {
				$resp = RestResponseFactory::ok(null, "Review not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'description'         => 'required',
					#'author_id'     => 'integer',
					#'diagnostic_id' => 'integer',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$review->description   = isset($request["description"])?$request["description"]:"";
			$review->diagnostic_id = isset($request["diagnostic_id"])?$request["diagnostic_id"]:"";
			$review->updated_by    = $currentUser->id;
			$review->save();

			$review->updated_by_name = User::find($review->updated_by)->fullName();
			$review->created_by_name = User::find($review->created_by)->fullName();

			//Timeline
			$diagnostic           = Diagnostic::find($review->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Diagnostic's review is update by " . $review->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($review->toArray(), "Review is update successful");
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
			$review = DiagnosticReview::find($id);
			if (!$review) {
				$resp = RestResponseFactory::ok(null, "Review not found.");
				return Response::json($resp);
			}

			//Timeline
			$diagnostic           = Diagnostic::find($review->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Diagnostic's review is delete by " . $review->created_by_name;
			$timeline->save();

			$review->delete();
			$resp = RestResponseFactory::ok(null, "Review is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'diagnostic_review.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("diagnostic_review")
			->select(DB::raw("diagnostic_review.*, CONCAT_WS(' ', CR.first_name, CR.last_name) as created_by_name, CONCAT_WS(' ', UD.first_name, UD.last_name) as updated_by_name"))
			//->leftJoin("diagnostic", "diagnostic_review.diagnostic_id", "=", "diagnostic.id")
			->leftJoin("users_meta as CR", "diagnostic_review.created_by", "=", "CR.user_id")
			->leftJoin("users_meta as UD", "diagnostic_review.updated_by", "=", "UD.user_id");

		if ($q) {
			$query->whereRaw("diagnostic_review.description LIKE ?", array('%'.$q.'%'));
		}
		if ($diagnostic_id = Input::get('diagnostic_id')) {
			$query->where('diagnostic_review.diagnostic_id', $diagnostic_id);
		}

		$query->groupBy("diagnostic_review.id");
		$query->orderBy($sort, $order);

		$review = $query->paginate($limit);
		if ($review) {
			$resp = RestResponseFactory::ok($review->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Review(s) not found.");
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
			$review = DiagnosticReview::find($id);
		//}
		if ($review) {
			$review->updated_by_name = User::find($review->updated_by)->fullName();
			$review->created_by_name = User::find($review->created_by)->fullName();
			$resp = RestResponseFactory::ok($review->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "DiagnosticReview(s) not found.");
		}
		return Response::json($resp);
	}
}