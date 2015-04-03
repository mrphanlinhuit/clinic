<?php

class ApiMovementsController extends BaseApiController {
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
					'created_by' => 'required',
					'invoice_id' => 'required',
					'bond_id' => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$movement             = new Movements();
			$movement->invoice_id = $request["invoice_id"];
			$movement->bond_id    = $request["bond_id"];
			$movement->created_by = $currentUser->id;
			$movement->save();

			$resp = RestResponseFactory::ok($movement->toArray(), "Movements is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}
	/**
	 * [spending description]
	 * @return [type] [description]
	 */
	public
	function spending() {
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
					'amount'       => 'required|numeric',
					'payment_type' => 'required|in:bond,money,card',
					'movements'    => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$movement               = new Movements();
			$movement->amount       = $request["amount"];
			$movement->payment_type = $request["payment_type"];
			$movement->movements_id = $request["movements"]["id"];
			$movement->bond_id      = isset($request["movements"]["bond_id"]) ? $request["movements"]["bond_id"]: null;
			$movement->note         = "spending: ".$request["freetext"];
			$movement->created_by   = $currentUser->id;
			$movement->save();



			$resp = RestResponseFactory::ok($movement->toArray(), "Movements is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}
	/**
	 * [devolution description]
	 * @return [type] [description]
	 */
	public
	function devolution() {
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
					'amount'       => 'required_without:' + $request["movements"]["amount"],
					'payment_type' => 'required|in:bond,money,card',
					'movements'    => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$movement               = new Movements();
			$movement->amount       = $request["amount"];
			$movement->payment_type = $request["payment_type"];
			$movement->movements_id = isset($request["movements"]["id"]) ? $request["movements"]["id"] : null;
			$movement->bond_id      = isset($request["movements"]["bond_id"]) ? $request["movements"]["bond_id"]: null;
			$movement->note         = "devolution: ";
			$movement->created_by   = $currentUser->id;
			$movement->save();

			$resp = RestResponseFactory::ok($movement->toArray(), "Movements is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 * [update description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public
	function update($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$movement = Movements::find($id);
			if (!$movement) {
				$resp = RestResponseFactory::ok(null, "Movements not found.");
				return Response::json($resp);
			}

			$validator = Validator::make(
				array(
					$request,
					'invoice_id' => 'required',
					'bond_id'    => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$movement->invoice_id = $request["invoice_id"];
			$movement->bond_id    = $request["bond_id"];
			$movement->save();

			$resp = RestResponseFactory::ok($movement->toArray(), "Movements is update successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 * [delete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public
	function delete($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$movement = Movements::find($id);
			if (!$movement) {
				$resp = RestResponseFactory::ok(null, "Movements not found.");
				return Response::json($resp);
			}

			$movement->delete();

			$resp = RestResponseFactory::ok(null, "Movements is delete successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 * [search description]
	 * @return [type] [description]
	 */
	public
	function search() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort  = Input::get('sort') ? Input::get('sort') : 'movements.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query

		// CONCAT_WS('', invoices_sent.id, invoices_providers.number) AS invoice,
		// CONCAT_WS('', movements.note, bondtype.name, invoices_providers.description) AS title,

		$query = DB::table("movements")->select(DB::raw("movements.*, invoices_sent.id AS invoice,
			CONCAT_WS('', movements.note, invoices_providers.name, invoices_providers.description, bondtype.name) AS title,
			CONCAT_WS(' ', ac.first_name, ac.last_name) AS invoice_by,
			CONCAT_WS(' ', pt.first_name, pt.last_name) AS patient"))
			->leftJoin("invoices_sent", "movements.invoices_sent_id", "=", "invoices_sent.id")
			->leftJoin("invoices_providers", "movements.invoices_providers_id", "=", "invoices_providers.id")
			->leftJoin("bonds", "bonds.id", "=", "movements.bond_id")
			->leftJoin("bondtype", "bondtype.id", "=", "bonds.bondtype_id")
			->leftJoin("users_meta AS pt", "pt.user_id", "=", "bonds.user_id")
			->leftJoin("users_meta AS ac", "ac.user_id", "=", "movements.created_by");

		if ($user_id = Input::get('user_id')) {
			$query->where('pt.user_id', $user_id);
		}

		if (($begin = Input::get('begin'))&&($parse = date_parse($begin))) {
			$date = $parse["year"]."-".$parse["month"]."-".$parse["day"];
			$query->whereRaw('DATE(movements.created_at) >= ?', array($date));
		}

		if (($end = Input::get('end'))&&($parse = date_parse($end))) {
			$date = $parse["year"]."-".$parse["month"]."-".$parse["day"];
			$query->whereRaw('DATE(movements.created_at) <= ?', array($date));
		}

		if (($date = Input::get('date'))&&($parse = date_parse($date))) {
			$date = $parse["year"]."-".$parse["month"]."-".$parse["day"];
			$query->whereRaw('DATE(movements.created_at) = ?', array($date));
		}

		if ($week = Input::get('week')) {
			//yearweek(`date`) = yearweek(curdate())
			$query->whereRaw('YEARWEEK(movements.created_at) = YEARWEEK(?)', array($week));
		}

		if ($month = Input::get('month')) {
			// MONTH(postdate) = MONTH(CURDATE()) AND YEAR(postdate) = YEAR(CURDATE())
			$query->whereRaw('MONTH(movements.created_at) = MONTH(?) AND YEAR(movements.created_at) = YEAR(?)', array($month,$month));
		}

		if ($year = Input::get('year')) {
			// YEAR(postdate) = YEAR(CURDATE())
			$query->whereRaw('YEAR(movements.created_at) = YEAR(?)', array($year));
		}
		if ($currentUser->isAdmin()) {

		}

		$query->groupBy("movements.id");
		$query->orderBy($sort, $order);

		$rules = $query->paginate($limit);
		if ($rules) {
			$resp = RestResponseFactory::ok($rules->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Movements(s) not found.");
		}
		return Response::json($resp);
	}
}