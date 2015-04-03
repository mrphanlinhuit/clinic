<?php

class ApiBondController extends BaseApiController {


	/**
	 * [create description]
	 * @return [type] [description]
	 */
	public
	function create() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'      => 'required|unique:bondtype',
					'session'   => 'required|integer|min:1',
					'timetouse' => 'required|integer|min:0',
					'tax'       => 'required|numeric|min:0',
					'price'     => 'required|numeric|min:0',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors).
					"</ul>");
				return Response::json($resp);
			}

			$bondtype = new BondType();
			$bondtype->name = $request["name"];
			$bondtype->session = isset($request["session"]) ? $request["session"] : "";
			$bondtype->price = isset($request["price"]) ? $request["price"] : "";
			$bondtype->tax = isset($request["tax"]) ? $request["tax"] : "";
			$bondtype->timetouse = isset($request["timetouse"]) ? $request["timetouse"] : "";
			$bondtype->status = isset($request["status"]) ? $request["status"] : false;
			$bondtype->save();

			$resp = RestResponseFactory::ok($bondtype->toArray(), "Bond is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function add() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					"user_id" => 'required',
					"bond"    => 'required',
					// "id" => 'required|unique:bonds,bondtype_id,NULL,id,user_id,' . $request["user_id"] 
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$bond              = new Bonds();
			$bond->user_id     = $request["user_id"];
			$bond->bondtype_id = $request["bond"]["id"];
			$bond->expired_at  = date('Y-m-d H:i:s', strtotime("+ " . $request["bond"]["timetouse"] . " days"));
			$bond->save();

			$movements             = new Movements;
			$movements->bond_id    = $bond->id;
			$movements->amount     = $request["bond"]["price"];
			$movements->created_by = $currentUser->id;
			$movements->save();

			//Timeline
			$bondtype             = BondType::find($bond->bondtype_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $bond->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = $bondtype->name." is add by ".$currentUser->fullName();
			$timeline->save();

			$resp = RestResponseFactory::ok($bond->toArray(), "Bond is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function remove($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$bond = Bonds::find($id);
			if (!$bond) {
				$resp = RestResponseFactory::ok(null, "Bond not found.");
				return Response::json($resp);
			}

			//Timeline
			$bondtype = BondType::find($bond->bondtype_id);
			$timeline = new Timeline();
			$timeline->user_id = $bond->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note = $bondtype->name." is remove by ".$currentUser->fullName();
			$timeline->save();

			$bond->delete();
			$resp = RestResponseFactory::ok(null, "Bond is delete successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function update($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$bondtype = BondType::find($id);
			if (!$bondtype) {
				$resp = RestResponseFactory::ok(null, "Bond not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'      => 'required|unique:bondtype,name,'.$bondtype->id,
					'session'   => 'required|integer|min:1',
					'timetouse' => 'required|integer|min:0',
					'tax'       => 'required|numeric|min:0',
					'price'     => 'required|numeric|min:0',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$bondtype->name      = $request["name"];
			$bondtype->session   = isset($request["session"]) ? $request["session"] : "";
			$bondtype->price     = isset($request["price"]) ? $request["price"] : "";
			$bondtype->tax       = isset($request["tax"]) ? $request["tax"] : "";
			$bondtype->timetouse = isset($request["timetouse"]) ? $request["timetouse"] : "";
			$bondtype->status    = isset($request["status"]) ? $request["status"] : false;
			$bondtype->save();

			$resp = RestResponseFactory::ok($bondtype->toArray(), "Bond is update successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function delete($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$bondtype = BondType::find($id);
			if (!$bondtype) {
				$resp = RestResponseFactory::ok(null, "Bond not found.");
				return Response::json($resp);
			}

			$bondtype->delete();

			$resp = RestResponseFactory::ok(null, "Bond is delete successful");
		} else {
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
	public
	function search() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort = Input::get('sort') ? Input::get('sort') : 'bondtype.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("bonds")->select(DB::raw("bonds.*, bondtype.name AS name, bondtype.session AS sessions, bondtype.price AS price, movements.id AS movements_id,
			CONCAT_WS(' ', users_meta.first_name, users_meta.last_name) AS patient, users_meta.address1 AS address, users_meta.national_id AS fiscalcode"))
			->leftJoin("bondtype", "bonds.bondtype_id", "=", "bondtype.id")
			->leftJoin("movements", "movements.bond_id", "=", "bonds.id")
			->leftJoin("users_meta", "bonds.user_id", "=", "users_meta.user_id")
			->where('movements.id', '>', 0);

		if ($status = Input::get('status')) {
			$query->where('bondtype.status', $status);
		}

		if ($currentUser->isAdmin()) {
			if ($q) {
				$query->whereRaw("bondtype.name LIKE ?", array('%'.$q.'%'));
			}

			if ($user_id = Input::get('user_id')) {
				$query->where('bonds.user_id', $user_id);
			}
		} else {
			$query->where('bonds.user_id', $currentUser->id);
		}

		$query->groupBy("bonds.id");
		$query->orderBy($sort, $order);

		$bondtype = $query->paginate($limit);
		if ($bondtype) {
			$resp = RestResponseFactory::ok($bondtype->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Bond not found.");
		}
		return Response::json($resp);
	}


	public
	function bondtype() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort = Input::get('sort') ? Input::get('sort') : 'bondtype.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("bondtype")->select(DB::raw("bondtype.*"));

		if ($status = Input::get('status')) {
			$query->where('bondtype.status', $status);
		}

		if ($currentUser->isAdmin()) {
			if ($q) {
				$query->whereRaw("bondtype.name LIKE ?", array('%'.$q.'%'));
			}

			if ($user_id = Input::get('user_id')) {
				$query->where('bonds.user_id', $user_id);
			}
		} else {
			$query->where('bonds.user_id', $currentUser->id);
		}

		$query->groupBy("bondtype.id");
		$query->orderBy($sort, $order);

		$bondtype = $query->paginate($limit);
		if ($bondtype) {
			$resp = RestResponseFactory::ok($bondtype->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Bond not found.");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc Get user by id
	 **/
	public
	function getById($id) {
		//$authToken = App::make('authToken');
		//if ($authToken->user->isAdmin()) {
		$bondtype = BondType::find($id);
		//}
		if ($bondtype) {
			$resp = RestResponseFactory::ok($bondtype->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Bond not found.");
		}
		return Response::json($resp);
	}
}