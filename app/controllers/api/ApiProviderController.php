<?php

/**
 *  @desc ApiProviderController
 **/
class ApiProviderController extends BaseApiController
{
		public function create()
		{
				$authToken   = App::make('authToken');
				$currentUser = $authToken->user;
				$resp        = "";
				if ($currentUser->isAdmin()) {
					// Code
					$errors      = array();
					$requestBody = file_get_contents('php://input');
					$request     = json_decode($requestBody, true);
					$validator   = Validator::make($request, array(
						'name'       => 'required|unique:providers',
					));
					if ($validator->fails())
						$errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
					if (count($errors) > 0) {
						$resp = RestResponseFactory::badrequest(null, "<ul>" . implode("", $errors) . "</ul>");
						return Response::json($resp);
					}

					$provider             = new Provider();
					$provider->name       = isset($request["name"])?$request["name"]:"";
					$provider->address    = isset($request["address"])?$request["address"]:"";
					$provider->fiscalcode = isset($request["fiscalcode"])?$request["fiscalcode"]:"";
					$provider->country    = isset($request['country']) ? $request['country'] : "";
					$provider->province   = isset($request['province']) ? $request['province'] : "";
					$provider->city       = isset($request['city']) ? $request['city'] : "";
					$provider->postal     = isset($request['postal']) ? $request['postal'] : "";
					$provider->fax        = isset($request['fax']) ? $request['fax'] : "";
					$provider->phone      = isset($request['phone']) ? $request['phone'] : "";
					$provider->mobile     = isset($request['mobile']) ? $request['mobile'] : "";
					$provider->email      = isset($request['email']) ? $request['email'] : "";
					// $provider->vat_id     = isset($request['vat_id']) ? $request['vat_id'] : "";
					$provider->note       = isset($request['note']) ? $request['note'] : "";
					$provider->created_by = $currentUser->id;
					$provider->updated_by = $currentUser->id;

					$provider->save();

					$resp = RestResponseFactory::ok($provider->toArray(), "Provider is create successful");
				} else {
						$resp = RestResponseFactory::ok(null, "User not permission.");
				}
				return Response::json($resp);
		}

		public function update($id)
		{
				$authToken   = App::make('authToken');
				$currentUser = $authToken->user;
				$resp        = "";
				if ($currentUser->isAdmin()) {
						// Code
						$requestBody = file_get_contents('php://input');
						$request     = json_decode($requestBody, true);

						$provider = Provider::find($id);
						if (!$provider) {
								$resp = RestResponseFactory::ok(null, "Provider not found.");
								return Response::json($resp);
						}

						$errors    = array();
						$validator = Validator::make($request, array(
							'name' => 'required|unique:providers,name,' . $provider->id,
						));
						if ($validator->fails())
							$errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
						if (count($errors) > 0) {
							$resp = RestResponseFactory::badrequest(null, "<ul>" . implode("", $errors) . "</ul>");
							return Response::json($resp);
						}
						$provider->fax        = isset($request['fax']) ? $request['fax'] : $provider->fax;
						$provider->name       = isset($request["name"]) ? $request["name"]: $provider->name;
						$provider->city       = isset($request['city']) ? $request['city'] : $provider->city;
						$provider->email      = isset($request['email']) ? $request['email'] : $provider->email;
						// $provider->vat_id     = isset($request['vat_id']) ? $request['vat_id'] : $provider->vat_id;
						$provider->phone      = isset($request['phone']) ? $request['phone'] : $provider->phone;
						$provider->mobile     = isset($request['mobile']) ? $request['mobile'] : $provider->mobile;
						$provider->postal     = isset($request['postal']) ? $request['postal'] : $provider->postal;
						$provider->address    = isset($request["address"]) ? $request["address"]: $provider->address;
						$provider->country    = isset($request['country']) ? $request['country'] : $provider->country;
						$provider->province   = isset($request['province']) ? $request['province'] : $provider->province;
						$provider->fiscalcode = isset($request["fiscalcode"]) ? $request["fiscalcode"]: $provider->fiscalcode;
						$provider->note       = isset($request['note']) ? $request['note'] : $provider->note;
						$provider->updated_by = $currentUser->id;
						$provider->save();

						$resp = RestResponseFactory::ok($provider->toArray(), "Provider is update successful");
				} else {
						$resp = RestResponseFactory::ok(null, "User not permission.");
				}
				return Response::json($resp);
		}

		public function delete($id)
		{
			$authToken   = App::make('authToken');
			$currentUser = $authToken->user;
			$resp        = "";
			if ($currentUser->isAdmin()) {
				// Code
				$provider = Provider::find($id);
				if (!$provider) {
						$resp = RestResponseFactory::ok(null, "Provider not found.");
						return Response::json($resp);
				}

				$provider->delete();

				$resp = RestResponseFactory::ok(null, "Provider is delete successful");
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
		public function search()
		{
			$authToken   = App::make('authToken');
			$currentUser = $authToken->user;

			$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
			$sort  = Input::get('sort') ? Input::get('sort') : 'id';
			$order = Input::get('order') ? Input::get('order') : 'asc';
			$q     = Input::get('q') ? Input::get('q') : null;
			//$uri = Request::path();

			// stats query
			$query = DB::table("providers")->select(DB::raw("providers.*"));

			if ($q) {
				$query->whereRaw("providers.name LIKE ?", array('%' . $q . '%'));
			}

			$query->groupBy("providers.id");
			$query->orderBy($sort, $order);

			$provider = $query->paginate($limit);
			if ($provider) {
				$resp = RestResponseFactory::ok($provider->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Provider(s) not found.");
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
			$provider = Provider::find($id);
			//}
			if ($provider) {
				if ($provider->updated_by>0) $provider->updated_by_name = User::find($provider->updated_by)->fullName();
				if ($provider->created_by>0) $provider->created_by_name = User::find($provider->created_by)->fullName();
				$resp = RestResponseFactory::ok($provider->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Provider(s) not found.");
			}
			return Response::json($resp);
		}
}