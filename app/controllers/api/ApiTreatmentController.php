<?php

class ApiTreatmentController extends BaseApiController {

	/**
	 *  @desc Treatment
	 **/
	public function all()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser){
			// Code
			$all = Treatment::all();
			$treatments = array();
			foreach ($all as $a) $treatments[] = array("key" => $a->id, "label" => $a->name);
			$resp = RestResponseFactory::ok($treatments);
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
					'name'     => 'required|unique:treatment',
					'price'    => 'required|numeric|min:0',
					'duration' => 'required|numeric|min:0',
					'tax'      => 'required|numeric|min:0',
					'role'     => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$treatment           = new Treatment();
			$treatment->name     = $request["name"];
			$treatment->duration = isset($request["duration"])?$request["duration"]:"";
			$treatment->price    = isset($request["price"])?$request["price"]:"";
			$treatment->tax      = isset($request["tax"])?$request["tax"]:"";
			$treatment->role     = isset($request["role"])?$request["role"]:"";
			$treatment->active   = isset($request["active"])?$request["active"]:false;
			$treatment->save();

			$resp = RestResponseFactory::ok($treatment->toArray(), "Treatment is create successful");
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

			$treatment = Treatment::find($id);
			if (!$treatment) {
				$resp = RestResponseFactory::ok(null, "Treatment not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'     => 'required|unique:treatment,name,' . $treatment->id,
					'price'    => 'required|numeric|min:0',
					'duration' => 'required|numeric|min:0',
					'tax'      => 'required|numeric|min:0',
					'role'     => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$treatment->name     = $request["name"];
			$treatment->duration = isset($request["duration"])?$request["duration"]:"";
			$treatment->price    = isset($request["price"])?$request["price"]:"";
			$treatment->tax      = isset($request["tax"])?$request["tax"]:"";
			$treatment->role     = isset($request["role"])?$request["role"]:"";
			$treatment->active   = isset($request["active"])?$request["active"]:false;
			$treatment->save();

			$resp = RestResponseFactory::ok($treatment->toArray(), "Treatment is update successful");
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
			$treatment = Treatment::find($id);
			if (!$treatment) {
				$resp = RestResponseFactory::ok(null, "Treatment not found.");
				return Response::json($resp);
			}

			$treatment->delete();

			$resp = RestResponseFactory::ok(null, "Treatment is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'treatment.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("treatment")->select(DB::raw("treatment.*"));

		if ($q) {
			$query->whereRaw("treatment.name like ?", array('%'.$q.'%'));
		}

		if ($active = Input::get('active')) {
			$query->where('treatment.active', $active);
		}

		$query->groupBy("treatment.id");
		$query->orderBy($sort, $order);

		$treatment = $query->paginate($limit);
		if ($treatment) {
			$resp = RestResponseFactory::ok($treatment->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Treatment(s) not found.");
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
			$treatment = Treatment::find($id);
		//}
		if ($treatment) {
			$resp = RestResponseFactory::ok($treatment->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Treatment(s) not found.");
		}
		return Response::json($resp);
	}
}