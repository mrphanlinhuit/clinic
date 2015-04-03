<?php

class ApiAmendmentController extends BaseApiController {
	public
	function create() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$errors = array();
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);
			$validator = Validator::make($request, array(
				'name' => 'required|unique:invoices_amendment',
			));
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$invoice = new InvoicesAmendment();
			$invoice->name = isset($request['name']) ? $request['name'] : "";
			$invoice->date = isset($request['date']) ? $request['date'] : "";
			$invoice->taxes = isset($request['taxes']) ? $request['taxes'] : "";
			$invoice->number = isset($request['number']) ? $request['number'] : "";
			$invoice->amount = isset($request['amount']) ? $request['amount'] : "";
			$invoice->description = isset($request['description']) ? $request['description'] : "";
			$invoice->created_by = $currentUser->id;
			$invoice->updated_by = $currentUser->id;
			$invoice->save();

			$resp = RestResponseFactory::ok($invoice->toArray(), "Invoices Amendment is create successful");
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

			$invoice = InvoicesAmendment::find($id);
			if (!$invoice) {
				$resp = RestResponseFactory::ok(null, "Invoices Amendment not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make($request, array(
				'name' => 'required|unique:invoices_amendment,name,'.$invoice->id,
			));
			if ($validator->fails())
				$errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors).
					"</ul>");
				return Response::json($resp);
			}
			$invoice->name = isset($request['name']) ? $request['name'] : $invoice->name;
			$invoice->date = isset($request['date']) ? $request['date'] : $invoice->date;
			$invoice->taxes = isset($request['taxes']) ? $request['taxes'] : $invoice->taxes;
			$invoice->number = isset($request['number']) ? $request['number'] : $invoice->number;
			$invoice->amount = isset($request['amount']) ? $request['amount'] : $invoice->amount;
			$invoice->description = isset($request['description']) ? $request['description'] : $invoice->description;
			$invoice->updated_by = $currentUser->id;
			$invoice->save();

			$resp = RestResponseFactory::ok($invoice->toArray(), "Invoices Amendment is update successful");
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
			$invoice = InvoicesAmendment::find($id);
			if (!$invoice) {
				$resp = RestResponseFactory::ok(null, "Invoices Amendment not found.");
				return Response::json($resp);
			}
			$invoice->delete();

			$resp = RestResponseFactory::ok(null, "Invoices Amendment is delete successful");
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
		$sort = Input::get('sort') ? Input::get('sort') : 'id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("invoices_amendment")->select(DB::raw("invoices_amendment.*"));

		if ($q) {
			$query->whereRaw("invoices_amendment.name LIKE ?", array('%'.$q.'%'));
		}

		$query->groupBy("invoices_amendment.id");
		$query->orderBy($sort, $order);

		$invoices = $query->paginate($limit);
		if ($invoices) {
			$resp = RestResponseFactory::ok($invoices->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Invoices Amendment(s) not found.");
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
		$invoice = InvoicesAmendment::find($id);
		//}
		if ($invoice) {
			$resp = RestResponseFactory::ok($invoice->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Invoices Amendment(s) not found.");
		}
		return Response::json($resp);
	}
}