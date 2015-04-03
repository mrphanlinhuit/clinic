<?php

class ApiPatientController extends BaseApiController {

	/**
	 *  @desc autocomplete
	 **/
	public function autocomplete()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$validator = Validator::make(
			Input::all(),
			array(
				'q' => 'required|min:2',
			)
		);
		if ($validator->fails())
		{
			$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $validator->messages()->all('<li>:message</li>'))."</ul>");
			return Response::json($resp);
		}

		$q = Input::get('q');
		$query = DB::table("users_meta")
			->select(DB::raw("`users_meta`.`user_id` as id, CONCAT_WS(' ', users_meta.`first_name`, users_meta.`last_name`) as name"))
			->leftJoin("role_user", "role_user.user_id", "=", "users_meta.user_id")
			->where('role_user.role_id', 6); // database/seeds/RoleTableSeeder.php line 137

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `users_meta`.`first_name`, `users_meta`.`last_name`) like ?", array('%'.$q.'%'));
		}

		if (!$currentUser->isAdmin()) {
			$query->where('users.status', 'active');
		} else {
			if ($status = Input::get('status')) {
				$query->where('users.status', $status);
			}
		}
		$query->orderBy('name', 'asc');
		$results = $query->get();

		if ($results) {
			$resp = RestResponseFactory::ok($results);
		} else {
			$resp = RestResponseFactory::ok(array(), "User(s) not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Get user by id
	 **/
	public function getById($id)
	{
		$authToken = App::make('authToken');
		if ($authToken->user->isAdmin()) {
			$user = User::with('credential', 'meta', 'roles', 'diagnotisc')->find($id); // show device
		} else {
			$user = User::with('credential', 'meta', 'roles')->find($id);
		}

		if ($user) {
			if ($user->hasRole('user')) $user->patient;
			$user->role_id = $user->getRoles();
			$user->updated_by_name = User::find($user->meta->updated_by)->fullName();
			$user->created_by_name = User::find($user->meta->created_by)->fullName();

			$resp = RestResponseFactory::ok($user->toArray());
		} else {
			$resp = RestResponseFactory::ok(null, "User not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Get me
	 **/
	public function getMe()
	{
		$authToken   = App::make('authToken');
		$currentUser = $authToken->user;
		$currentUser->credential;
		$currentUser->meta;
		#$currentUser->message;
		$currentUser->roles;
		$currentUser->role_id = $currentUser->getRoles();

		if ($authToken->user->isAdmin()) {
			$currentUser->clinic;
		}

		if ($currentUser) {
			$currentUser->updated_by_name = User::find($currentUser->meta->updated_by)->fullName();
			$currentUser->created_by_name = User::find($currentUser->meta->created_by)->fullName();
			$resp = RestResponseFactory::ok($currentUser->toArray());
		} else {
			$resp = RestResponseFactory::ok(null, "User not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Update user
	 **/
	public function update($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($currentUser){
			$user = User::with('meta', 'roles', 'diagnotisc')->find($id);
			if ($user) {
				if (($id == $currentUser->id)&&($currentUser->isAdmin())) {


					$resp = RestResponseFactory::ok($user->toArray());
				}
				else {
					$resp = RestResponseFactory::forbidden(null, "User not permission.");
				}

			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not found.");
			}
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc update user Bond
	 **/
	public function updateBond($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($currentUser){
			$user = User::with('meta', 'roles', 'diagnotisc')->find($id);
			if ($user) {
				if (($id == $currentUser->id)&&($currentUser->isAdmin())) {
					//code


					$resp = RestResponseFactory::ok($user->toArray());
				}
				else {
					$resp = RestResponseFactory::forbidden(null, "User not permission.");
				}

			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not found.");
			}
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 * [getBond description]
	 * @param  [type] $id [description]
	 * @return [jobject]  [description]
	 */
	public function getBond($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($currentUser){
			$user = User::with('bonds')->find($id);
			if ($user) {
				if (($id == $currentUser->id)&&($currentUser->isAdmin())) {
					$resp = RestResponseFactory::ok($user->toArray());
				}
				else {
					$resp = RestResponseFactory::forbidden(null, "User not permission.");
				}
			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not found.");
			}
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Delete user
	 **/
	public function deleteBond($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		$user = User::find($id);

		if ($user) {
			if ($currentUser->isAdmin()){
				//$user->delete();
				$resp = RestResponseFactory::ok(null);
			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not permission.");
			}
		} else {
			$resp = RestResponseFactory::forbidden(null, "User not found.");
		}
		return Response::json($resp);
	}


		/**
	 *  @desc update user Session
	 **/
	public function updateSession($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($currentUser){
			$user = User::with('meta', 'roles', 'diagnotisc')->find($id);
			if ($user) {
				if (($id == $currentUser->id)&&($currentUser->isAdmin())) {
					//code


					$resp = RestResponseFactory::ok($user->toArray());
				}
				else {
					$resp = RestResponseFactory::forbidden(null, "User not permission.");
				}

			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not found.");
			}
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc get user Session
	 **/
	public function getSession($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($currentUser){
			$user = User::with('meta', 'roles', 'diagnotisc')->find($id);
			if ($user) {
				if (($id == $currentUser->id)&&($currentUser->isAdmin())) {
					//code


					$resp = RestResponseFactory::ok($user->toArray());
				}
				else {
					$resp = RestResponseFactory::forbidden(null, "User not permission.");
				}

			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not found.");
			}
		}
		else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Delete user
	 **/
	public function deleteSession($id)
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		$user = User::find($id);

		if ($user) {
			if ($currentUser->isAdmin()){
				//$user->delete();
				$resp = RestResponseFactory::ok(null);
			}
			else {
				$resp = RestResponseFactory::forbidden(null, "User not permission.");
			}
		} else {
			$resp = RestResponseFactory::forbidden(null, "User not found.");
		}
		return Response::json($resp);
	}
}
