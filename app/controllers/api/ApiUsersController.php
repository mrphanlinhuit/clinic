<?php

class ApiUsersController extends BaseApiController {

	public
	function employee(){
		$sort = Input::get('sort') ? Input::get('sort') : 'users.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		//$uri = Request::path();

		// stats query
		$query = DB::table("users")->select(DB::raw("users.id, users_meta.first_name, users_meta.last_name"))
			->leftJoin("users_meta", "users_meta.user_id", "=", "users.id")
			->leftJoin("role_user", "role_user.user_id", "=", "users.id");

		if ($role = Input::get('role')) {
			$role_id = Role::where('name', $role)->first();
			$query->where('role_user.role_id', $role_id->id);
		} else $query->whereRaw('role_user.role_id IN (2,3)'); // Doctor and Therapist

		$query->groupBy("users.id");
		$query->orderBy($sort, $order);

		$users = $query->paginate(999);
		if ($users) {
			$resp = RestResponseFactory::ok($users->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Employee(s) not found.");
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
		$sort = Input::get('sort') ? Input::get('sort') : 'users.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("users")->select(DB::raw("users.id, 
			(SELECT GROUP_CONCAT(ur.name) FROM users_role AS ur LEFT JOIN role_user AS ru ON ur.id = ru.role_id 
				WHERE ru.user_id = users.id) AS group_name,
			users_credentials.email, users.status as user_status, users.created_at as created_at, users_meta.profile_img_url AS avatar,
			users_meta.first_name, users_meta.last_name, MAX(users_tokens.created_at) as last_login,
			(SELECT COUNT(*) FROM diagnostic WHERE diagnostic.user_id = users.id) AS treatment"))
			->leftJoin("users_meta", "users_meta.user_id", "=", "users.id")
			->leftJoin("users_credentials", "users_credentials.user_id", "=", "users.id")
			->leftJoin("users_tokens", "users_tokens.user_id", "=", "users.id")
			->leftJoin("diagnostic", "diagnostic.user_id", "=", "users.id")
			->leftJoin("role_user", "role_user.user_id", "=", "users.id");

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', `users_meta`.`first_name`, `users_meta`.`last_name`) like ?", array('%'.$q.'%'));
		}

		if (!$currentUser->isAdmin()) {
			$query->where('users.group_id', $currentUser->group_id); // member group can only search member
			$query->where('users.status', 'active'); // only actives
			$query->where("users.id", "!=", $currentUser->id);
		} else {
			// filter by role
			if ($role = Input::get('role')) {
				$role_id = Role::where('name', $role)->first();
				$query->where('role_user.role_id', $role_id->id);
			}
			// filter by treatment
			if ($treatment = Input::get('treatment')) {
				$query->where('diagnostic.id', '>', 0);
			}
			// filter by status
			if ($status = Input::get('status')) {
				$query->where('users.status', $status);
			}

		}
		$query->groupBy("users.id");
		$query->orderBy($sort, $order);

		$users = $query->paginate($limit);
		if ($users) {
			$resp = RestResponseFactory::ok($users->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "User(s) not found.");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc autocomplete
	 **/
	public
	function autocomplete() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$validator = Validator::make(
			Input::all(),
			array(
				'q' => 'required|min:1',
			)
		);
		if ($validator->fails()) {
			$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $validator->messages()->all('<li>:message</li>'))."</ul>");
			return Response::json($resp);
		}

		$q = Input::get('q');
		$query = DB::table("users")->select(DB::raw("`users`.`id` as id, CONCAT_WS(' ', users_meta.`first_name`, users_meta.`last_name`) as name"))->leftJoin("users_meta", "users_meta.user_id", "=", "users.id");

		if ($q) $query->whereRaw("CONCAT_WS(' ', `users_meta`.`first_name`, `users_meta`.`last_name`) like ?", array('%'.$q.'%'));
		if (!$currentUser->isAdmin()) {
			//	$query->where('users.group_id', $currentUser->group_id); // member group can only search member
			$query->where('users.status', 'active'); // only actives
			$query->where("users.id", "!=", $currentUser->id);
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
	public
	function getById($id) {
		$authToken = App::make('authToken');
		if ($id == 1) {
			$user = User::with('credential', 'meta', 'roles', 'clinic')->find($id); // show Clinic only Admin
		} else {
			$user = User::with('credential', 'meta', 'roles', 'tags')->find($id);
		}

		if ($user) {
			if ($user->hasRole('user')) $user->patient;
			$user->role_id = $user->getRoles();
			$user->updated_by = max($user->meta->updated_by, $user->updated_by);
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
	public
	function getMe() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$currentUser->credential;
		$currentUser->meta;
		$currentUser->group;
		$currentUser->billing;
		# $currentUser->message;
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
	 *  @desc Create user
	 **/
	public
	function create() {

		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody, true);

		$errors = array();
		$validator = Validator::make(
			$request,
			array(
				'group_id' => 'required|exists:users_group,id',
				'status'   => 'required|in:pending,active,inactive,banned'
			)
		);
		if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
		$validator = Validator::make(
			isset($request['meta']) ? $request['meta'] : array(),
			array(
				'first_name' => 'required',
				'last_name' => 'required',
				//'phone' => 'required',
			)
		);
		if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
		$validator = Validator::make(
			isset($request['credential']) ? $request['credential'] : array(),
			array(
				'email' => 'required|email|unique:users_credentials',
				//'password' => 'required|min:6',
			)
		);
		if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
		if (count($errors) > 0) {
			$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
			return Response::json($resp);
		}

		$user = new User();
		$user->group_id = $request['group_id'];
		$user->status = $request['status'];

		$user->save();

		$userCredential = new UserCredential();
		$userCredential->user_id = $user->id;
		$userCredential->email = $request['credential']['email'];
		$userCredential->password = Hash::make($request['credential']['password']);
		$userCredential->save();


		if (isset($request['role_id'])) {
			$user->addRole($request['role_id']);
		}
		if ($user->hasRole('user')) {
			$userPatient = new Patient();
			$userPatient->user_id = $user->id;
			$userPatient->note = isset($request['patient']['note']) ? $request['patient']['note'] : "";
			$userPatient->save();
		}

		$updated_by = (isset($currentUser->id)) ? $currentUser->id : $user->id;
		$userMeta                      = new UserMeta();
		$userMeta->user_id             = $user->id;
		$userMeta->first_name          = isset($request['meta']['first_name']) ? $request['meta']['first_name'] : "";
		$userMeta->last_name           = isset($request['meta']['last_name']) ? $request['meta']['last_name'] : "";
		$userMeta->alias               = isset($request['meta']['alias']) ? $request['meta']['alias'] : "";
		$userMeta->gender              = isset($request['meta']['gender']) ? $request['meta']['gender'] : "";
		$userMeta->birthday            = isset($request['meta']['birthday']) ? $request['meta']['birthday'] : null;
		$userMeta->national_id         = isset($request['meta']['national_id']) ? $request['meta']['national_id'] : "";
		$userMeta->job                 = isset($request['meta']['job']) ? $request['meta']['job'] : "";
		$userMeta->address1            = isset($request['meta']['address1']) ? $request['meta']['address1'] : "";
		$userMeta->address2            = isset($request['meta']['address2']) ? $request['meta']['address2'] : "";
		$userMeta->country             = isset($request['meta']['country']) ? $request['meta']['country'] : "";
		$userMeta->province            = isset($request['meta']['province']) ? $request['meta']['province'] : "";
		$userMeta->city                = isset($request['meta']['city']) ? $request['meta']['city'] : "";
		$userMeta->postal              = isset($request['meta']['postal']) ? $request['meta']['postal'] : "";
		$userMeta->fax                 = isset($request['meta']['fax']) ? $request['meta']['fax'] : "";
		$userMeta->phone               = isset($request['meta']['phone']) ? $request['meta']['phone'] : "";
		$userMeta->mobile              = isset($request['meta']['mobile']) ? $request['meta']['mobile'] : "";
		$userMeta->company             = isset($request['meta']['company']) ? $request['meta']['company'] : "";
		$userMeta->website_url         = isset($request['meta']['website_url']) ? $request['meta']['website_url'] : "";
		$userMeta->referer             = isset($request['meta']['referer']) ? $request['meta']['referer'] : "";
		$userMeta->profile_img_url     = isset($request['meta']['profile_img_url']) ? $request['meta']['profile_img_url'] : "";
		$userMeta->professional_number = isset($request['meta']['professional_number']) ? $request['meta']['professional_number'] : "";
		$userMeta->created_by          = $updated_by;
		$userMeta->updated_by          = $updated_by;
		$userMeta->save();


		if (isset($request['tags'])) {
			foreach ($request['tags'] as $tag) {
				$userTag          = new Tags;
				$userTag->user_id = $user->id;
				$userTag->name    = $tag["name"];
				$userTag->save();
			}
		}

		// Timeline
		$timeline = new Timeline();
		$timeline->user_id = $user->id;
		$timeline->created_by = $updated_by;
		$timeline->note = "User is register by ".User::find($updated_by)->fullName();
		$timeline->save();


		//NotificationService::newUser($user);

		$resp = RestResponseFactory::ok($user->toArray());
		return Response::json($resp);
	}

	/**
	 *  @desc Update user
	 **/
	public
	function update($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		if ($id == 'me') {
			$id = $currentUser->id;
		} else {
			if (!$currentUser->isAdmin()) {
				$resp = RestResponseFactory::forbidden("", "Can't update other user.");
				return Response::json($resp);
			}
		}

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody, true);

		$user = User::with('credential', 'meta', 'roles', 'clinic')->find($id);
		if (!$user) {
			$resp = RestResponseFactory::ok(null, "User not found.");
			return Response::json($resp);
		}


		$errors = array();
		$validator = Validator::make(
			$request,
			array(
				'group_id' => 'exists:users_group,id',
				'status' => 'in:pending,active,inactive,banned'
			)
		);
		if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

		if (isset($request['credential']['password'])) {
			$validator = Validator::make(
				isset($request['meta']) ? $request['meta'] : array(),
				array(
					'first_name' => 'required',
					'last_name' => 'required',
					//'phone' => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
		}
		$userCredential = $user->credential;

		// if change password
		if (isset($request['credential']['password']) && isset($request['credential']['newpassword'])) {
			$validation = Validator::make(
				isset($request['credential']) ? $request['credential'] : array(),
				array(
					'password' => 'required|min:6',
					'newpassword' => 'required|min:6|different:password',
					'confirmpassword' => 'required|same:newpassword',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			$credential = DB::table('users_credentials')->where('user_id', $user->id)->first();
			if (Hash::check($request['credential']['password'], $credential->password)) {
				$userCredential->password = Hash::make($request['credential']['newpassword']);
			}
		}

		// if change email
		if (isset($request['credential']['email']) && ($request['credential']['email'] !== $userCredential->email)) {
			$validator = Validator::make(
				isset($request['credential']) ? $request['credential'] : array(),
				array(
					'email' => 'required|email|unique:users_credentials,email,'.$id,
					'password' => 'min:6',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			$userCredential->email = isset($request['credential']['email']) ? $request['credential']['email'] : $userCredential->email;
		}

		// Count errors
		if (count($errors) > 0) {
			$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors).
				"</ul>");
			return Response::json($resp);
		}

		// for Admin
		$previousStatus = "";
		if ($currentUser->isAdmin()) {
			if (isset($request['credential']['password']) && $request['credential']['password'] != "") {
				$userCredential->password = Hash::make($request['credential']['password']);
			}
			// $previousStatus = $user->status;
			$user->status = isset($request['status']) ? $request['status'] : $user->status;

			if (isset($request['role_id'])) {
				$user->addRole($request['role_id']);
			}

			if (isset($request['clinic']) && ($user->id == 1)) {
				$userClinic = $user->clinic;
				$userClinic->user_id = $user->id;
				$userClinic->openhour1 = isset($request['clinic']['openhour1']) ? $request['clinic']['openhour1'] : $userClinic->openhour1;
				$userClinic->openhour2 = isset($request['clinic']['openhour2']) ? $request['clinic']['openhour2'] : $userClinic->openhour2;
				$userClinic->closehour1 = isset($request['clinic']['closehour1']) ? $request['clinic']['closehour1'] : $userClinic->closehour1;
				$userClinic->closehour2 = isset($request['clinic']['closehour2']) ? $request['clinic']['closehour2'] : $userClinic->closehour2;
				$userClinic->vat_id = isset($request['clinic']['vat_id']) ? $request['clinic']['vat_id'] : $userClinic->vat_id;
				$userClinic->notes = isset($request['clinic']['notes']) ? $request['clinic']['notes'] : $userClinic->notes;
				$userClinic->save();
			}
		}

		$userCredential->save();
		$user->save();
		$user->role_id = $user->getRoles();

		/*if ($previousStatus == 'pending' && $user->status == 'active') {
			NotificationService::userActivation($user);
		}*/

		$updated_by                    = (isset($currentUser)) ? $currentUser->id : $user->id;
		$userMeta                      = $user->meta;
		$userMeta->first_name          = isset($request['meta']['first_name']) ? $request['meta']['first_name'] : $userMeta->first_name;
		$userMeta->last_name           = isset($request['meta']['last_name']) ? $request['meta']['last_name'] : $userMeta->last_name;
		$userMeta->alias               = isset($request['meta']['alias']) ? $request['meta']['alias'] : $userMeta->alias;
		$userMeta->gender              = isset($request['meta']['gender']) ? $request['meta']['gender'] : $userMeta->gender;
		$userMeta->birthday            = isset($request['meta']['birthday']) ? $request['meta']['birthday'] : $userMeta->birthday;
		$userMeta->national_id         = isset($request['meta']['national_id']) ? $request['meta']['national_id'] : $userMeta->national_id;
		$userMeta->job                 = isset($request['meta']['job']) ? $request['meta']['job'] : $userMeta->job;
		$userMeta->address1            = isset($request['meta']['address1']) ? $request['meta']['address1'] : $userMeta->address1;
		$userMeta->address2            = isset($request['meta']['address2']) ? $request['meta']['address2'] : $userMeta->address2;
		$userMeta->country             = isset($request['meta']['country']) ? $request['meta']['country'] : $userMeta->country;
		$userMeta->province            = isset($request['meta']['province']) ? $request['meta']['province'] : $userMeta->province;
		$userMeta->city                = isset($request['meta']['city']) ? $request['meta']['city'] : $userMeta->city;
		$userMeta->postal              = isset($request['meta']['postal']) ? $request['meta']['postal'] : $userMeta->postal;
		$userMeta->fax                 = isset($request['meta']['fax']) ? $request['meta']['fax'] : $userMeta->fax;
		$userMeta->phone               = isset($request['meta']['phone']) ? $request['meta']['phone'] : $userMeta->phone;
		$userMeta->mobile              = isset($request['meta']['mobile']) ? $request['meta']['mobile'] : $userMeta->mobile;
		$userMeta->company             = isset($request['meta']['company']) ? $request['meta']['company'] : $userMeta->company;
		$userMeta->website_url         = isset($request['meta']['website_url']) ? $request['meta']['website_url'] : $userMeta->website_url;
		$userMeta->referer             = isset($request['meta']['referer']) ? $request['meta']['referer'] : $userMeta->referer;
		$userMeta->profile_img_url     = isset($request['meta']['profile_img_url']) ? $request['meta']['profile_img_url'] : $userMeta->profile_img_url;
		$userMeta->professional_number = isset($request['meta']['professional_number']) ? $request['meta']['professional_number'] : $userMeta->professional_number;
		$userMeta->updated_by          = $updated_by;
		$userMeta->save();

		if (isset($request['patient'])) {
			$userPatient          = $user->patient;
			if (!$userPatient) $userPatient = new Patient;
			$userPatient->user_id = isset($user->id) ? $user->id : $userPatient->user_id;
			$userPatient->note    = isset($request['patient']['note']) ? $request['patient']['note'] : "";
			$userPatient->save();
		}
		if (isset($request['tags'])) {
			$tags = $request['tags'];
			$_tags = Tags::where('user_id', '=', $user->id)->get();
			if ($_tags) foreach ($_tags as $d) $d->delete();
			// die(var_dump($request['tags']));
			foreach ($tags as $tag) {
				$userTag          = new Tags();
				$userTag->user_id = $user->id;
				$userTag->name    = $tag["name"];
				$userTag->save();
			}
		}
		$user->tags;
		$user->updated_by_name = User::find($user->meta->updated_by)->fullName();
		$user->created_by_name = User::find($user->meta->created_by)->fullName();

		// Timeline
		$timeline = new Timeline();
		$timeline->user_id = $user->id;
		$timeline->created_by = $updated_by;
		$timeline->note = "User is update by ".User::find($updated_by)->fullName();
		$timeline->save();

		$resp = RestResponseFactory::ok($user->toArray());
		return Response::json($resp);
	}

	/**
	 * [delete User by Id]
	 * @param  [integer] $id [description]
	 * @return [json]     [null]
	 */
	public
	function delete($id) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		$user = User::find($id);

		if ($user) {
			if ($currentUser->isAdmin()) {
				$user->delete();
				$resp = RestResponseFactory::ok(null);
			} else {
				$resp = RestResponseFactory::forbidden(null, "User not permission.");
			}
		} else {
			$resp = RestResponseFactory::forbidden(null, "User not found.");
		}
		return Response::json($resp);
	}
}