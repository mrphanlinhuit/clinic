<?php

class ApiInvoiceController extends BaseApiController {
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
			$errors = array();
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);
			$validator = Validator::make($request, array(
				'attachment' => 'required',
				'number'     => 'required|unique:invoices_providers',
				'date'       => 'required',
				'amount'     => 'required|numeric',
			));
			if ($validator->fails())
				$errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$invoice                = new InvoicesProviders();
			$invoice->name          = isset($request['name']) ? $request['name'] : "";
			$invoice->date          = isset($request['date']) ? $request['date'] : "";
			$invoice->taxes         = isset($request['taxes']) ? $request['taxes'] : "";
			$invoice->total         = isset($request['total']) ? $request['total'] : "";
			$invoice->number        = isset($request['number']) ? $request['number'] : "";
			$invoice->amount        = isset($request['amount']) ? $request['amount'] : "";
			$invoice->description   = isset($request['description']) ? $request['description'] : "";
			$invoice->provider_id   = isset($request['provider_id']) ? $request['provider_id'] : "";
			$invoice->attachment_id = isset($request['attachment']) ? $request['attachment'] : "";

			$invoice->created_by  = $currentUser->id;
			$invoice->updated_by  = $currentUser->id;
			$invoice->save();

			// make a movements
			$movements                        = new Movements;
			$movements->amount                = (-1 * $invoice->total);
			$movements->invoices_providers_id = $invoice->id;
			$movements->save();

			$resp = RestResponseFactory::ok($invoice->toArray(), "Invoices Provider is create successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 * [send description]
	 * @return [type] [description]
	 */
	public
	function send() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);
			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name'    => 'required',
					'address' => 'required',
					'bond'    => 'required',
					)
				);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

			$max = (float)$request["bond"]["price"];
			$validator = Validator::make(
				$request,
				array(
					'amount' => "required|numeric|max:$max"
					)
				);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

			if (isset($request["bond"])) {
				$validator = Validator::make(
					$request["bond"],
					array(
						'movements_id' => "required|integer|min:0"
						)
					);
				if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			}

			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$invoicesent             = new InvoicesSent;
			$invoicesent->name       = $request["name"];
			$invoicesent->amount     = $request["amount"];
			$invoicesent->address    = $request["address"];
			$invoicesent->fiscalcode = $request["fiscalcode"];
			$invoicesent->save();

			$invoicesamendment = new InvoicesAmendment;
			$invoicesamendment->invoices_sent_id = $invoicesent->id;
			$invoicesamendment->save();

			// make a movements
			$movements                   = new Movements;
			$movements->invoices_sent_id = $invoicesent->id;
			$movements->bond_id          = isset($request["bond"]["id"])?$request["bond"]["id"]:null;
			$movements->movements_id     = isset($request["bond"]["movements_id"])?$request["bond"]["movements_id"]:null;
			$movements->amount           = $invoicesent->amount;
			$movements->payment_type     = "bond";
			$movements->created_by       = $currentUser->id;
			$movements->save();

			$invoicesent->movements;
			$resp = RestResponseFactory::ok($invoicesent->toArray(), "Invoice is sent successful");
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

			$invoice = InvoicesProviders::find($id);
			if (!$invoice) {
				$resp = RestResponseFactory::ok(null, "Invoices Provider not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make($request, array(
				'name' => 'required|unique:invoices_provider,name,'.$invoice->id,
			));
			if ($validator->fails())
				$errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$invoice->name        = isset($request['name']) ? $request['name'] : $invoice->name;
			$invoice->date        = isset($request['date']) ? $request['date'] : $invoice->date;
			$invoice->taxes       = isset($request['taxes']) ? $request['taxes'] : $invoice->taxes;
			$invoice->number      = isset($request['number']) ? $request['number'] : $invoice->number;
			$invoice->amount      = isset($request['amount']) ? $request['amount'] : $invoice->amount;
			$invoice->description = isset($request['description']) ? $request['description'] : $invoice->description;
			$invoice->updated_by  = $currentUser->id;
			$invoice->save();

			$resp = RestResponseFactory::ok($invoice->toArray(), "Invoices Provider is update successful");
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
	function invoicesUpdate($id) {
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
			$invoice->amount = 0;
			$invoice->save();

			$resp = RestResponseFactory::ok(null, "Invoices Amendment is remove successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
		
	}

	
	/**
	 * [invoicesDelete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public
	function invoicesDelete($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			// Code
			$invoice = InvoicesSent::find($id);
			if (!$invoice) {
				$resp = RestResponseFactory::ok(null, "Invoices Client not found.");
				return Response::json($resp);
			}
			$invoice->delete();

			$resp = RestResponseFactory::ok(null, "Invoices Client is delete successful");
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
			$invoice = InvoicesProviders::find($id);
			if (!$invoice) {
				$resp = RestResponseFactory::ok(null, "Invoices Provider not found.");
				return Response::json($resp);
			}
			$invoice->delete();

			$resp = RestResponseFactory::ok(null, "Invoices Provider is delete successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function provider() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
			$sort  = Input::get('sort') ? Input::get('sort') : 'id';
			$order = Input::get('order') ? Input::get('order') : 'asc';
			$q     = Input::get('q') ? Input::get('q') : null;

			// stats query
			$query = DB::table("invoices_providers")->select(DB::raw("invoices_providers.*"));

			if ($q) $query->whereRaw("invoices_providers.name LIKE ?", array('%'.$q.'%'));

			$query->groupBy("invoices_providers.id");
			$query->orderBy($sort, $order);

			$providers = $query->paginate($limit);
			if ($providers) {
				$resp = RestResponseFactory::ok($providers->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Invoices Provider(s) not found.");
			}

		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 * [amendmentsBill description]
	 * @return [type] [description]
	 */
	public
	function amendmentsBill(){
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);

			$errors = array();
			$validator = Validator::make(
				$request, array(
					'invoice' => 'required',
					)
				);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

			$max = (float)$request["invoice"]["price"];

			$validator = Validator::make(
				$request,
				array(
					'amount' => "required|numeric|max:$max",
					)
				);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$invoicesamendment = InvoicesAmendment::where('invoices_sent_id', '=', $request["invoice"]["id"])->first();
			if ($invoicesamendment){
				$invoicesamendment->amount = $request["amount"];
				$invoicesamendment->save();
				$resp = RestResponseFactory::ok($invoicesamendment->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Invoices Amendments(s) not found.");
			}
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}
	/**
	 * [amendments description]
	 * @return [type] [description]
	 */
	public
	function amendments() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()) {
			$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
			$sort  = Input::get('sort') ? Input::get('sort') : 'id';
			$order = Input::get('order') ? Input::get('order') : 'asc';
			$q     = Input::get('q') ? Input::get('q') : null;

			$query = DB::table("invoices_sent")->select(DB::raw("invoices_amendment.*, invoices_sent.id AS corrected, CONCAT_WS(' ', ac.first_name, ac.last_name) as invoice_by, CONCAT_WS(' ', pt.first_name, pt.last_name) as patient"))
				->leftJoin("movements", "movements.invoices_sent_id", "=", "invoices_sent.id")
				->leftJoin("invoices_amendment", "invoices_amendment.invoices_sent_id", "=", "invoices_sent.id")
				->leftJoin("bonds", "bonds.id", "=", "movements.bond_id")
				->leftJoin("users_meta AS pt", "pt.user_id", "=", "bonds.user_id")
				->leftJoin("users_meta AS ac", "ac.user_id", "=", "movements.created_by")
				->where("invoices_amendment.amount", "<>", "0");

			if ($q) $query->whereRaw("invoices_amendment.id LIKE ?", array('%'.$q.'%'));

			if ($user_id = Input::get('user_id')) {
				$query->where('bonds.user_id', $user_id);
			}

			$query->groupBy("invoices_amendment.id");
			$query->orderBy($sort, $order);

			$providers = $query->paginate($limit);
			if ($providers) {
				$resp = RestResponseFactory::ok($providers->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Invoices Amendments(s) not found.");
			}

		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 * [invoicesSent description]
	 * @return [type] [description]
	 */
	public
	function invoicesSent() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort  = Input::get('sort') ? Input::get('sort') : 'id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("invoices_sent")->select(DB::raw("invoices_sent.*, bondtype.price AS price, bondtype.name AS title, bondtype.tax AS taxes,
			invoices_amendment.amount AS amendment,	CONCAT_WS(' ', ac.first_name, ac.last_name) as invoice_by, CONCAT_WS(' ', pt.first_name, pt.last_name) as patient"))
			->leftJoin("movements", "movements.invoices_sent_id", "=", "invoices_sent.id")
			->leftJoin("invoices_amendment", "invoices_amendment.invoices_sent_id", "=", "invoices_sent.id")
			->leftJoin("bonds", "bonds.id", "=", "movements.bond_id")
			->leftJoin("bondtype", "bondtype.id", "=", "bonds.bondtype_id")
			->leftJoin("users_meta AS pt", "pt.user_id", "=", "bonds.user_id")
			->leftJoin("users_meta AS ac", "ac.user_id", "=", "movements.created_by");

		if ($q) $query->whereRaw("invoices_sent.name LIKE ?", array('%'.$q.'%'));

		if ($user_id = Input::get('user_id')) {
			$query->where('bonds.user_id', $user_id);
		}

		$query->groupBy("invoices_sent.id");
		$query->orderBy($sort, $order);

		$invoices = $query->paginate($limit);
		if ($invoices) {
			$resp = RestResponseFactory::ok($invoices->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Invoices receive not found.");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("invoices_providers")->select(DB::raw("invoices_providers.*, providers.name, providers.address, providers.fiscalcode, attachments.original_filename AS filename, attachments.filepath AS filepath"))
			->leftJoin("attachments", "attachments.id", "=", "invoices_providers.attachment_id")
			->leftJoin("providers", "providers.id", "=", "invoices_providers.provider_id");

		if ($q) $query->whereRaw("invoices_providers.name LIKE ?", array('%'.$q.'%'));

		if ($provider_id = Input::get('provider_id')) {
			$query->where('invoices_providers.provider_id', $provider_id);
		}

		$query->groupBy("invoices_providers.id");
		$query->orderBy($sort, $order);

		$invoices = $query->paginate($limit);
		if ($invoices) {
			$resp = RestResponseFactory::ok($invoices->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Invoices Provider(s) not found.");
		}
		return Response::json($resp);
	}

	/**
	 * [getById description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public
	function getById($id) {
		//$authToken = App::make('authToken');
		//if ($authToken->user->isAdmin()) {
		$invoice = InvoicesProviders::find($id);
		//}
		if ($invoice) {
			$resp = RestResponseFactory::ok($invoice->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Invoices Provider(s) not found.");
		}
		return Response::json($resp);
	}
}