<?php

class ApiSessionController extends BaseApiController {

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
					'patient'      => 'required',
					'diagnostic'   => 'required',
					'room'         => 'required',
					'scheduled_at' => 'required',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));

			if (isset($request["room"])&&($room = $request["room"])){
				$room["room_id"] = $room["id"];
				$validator = Validator::make(
					$room,
					array(
						'room_id'   => 'required|unique:sessions,' . 'scheduled_at,' . $request["scheduled_at"],
					)
				);
				if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			}

			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			// Add new patient for "first visit"
			if (!isset($request["patient"]["id"])){
				$name = explode(" ", $request["patient"]);
				$first_name = $name[0];
				array_shift($name);
				$last_name = implode(" ", $name);
				$phone = isset($request["phone"]) ? $request["phone"] : "";
				$newpatient = User::create(array(
					'group_id' => "3",
					'status'   => 'pending' // for "first visit"
				));
				// 6 is patient
				$newpatient->addRole(array("6"));
				UserCredential::create(array('user_id'  => $newpatient->id, 'email' => md5(time())."@clinic.com"));

				$meta = UserMeta::create(array(
					'user_id'    => $newpatient->id,
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'phone'      => $phone,
				));
				$meta->created_by = $currentUser->id;
				$meta->updated_by = $currentUser->id;
				$meta->save();
			}

			if (!isset($request["diagnostic"]["id"])){
				$diagnostic             = new Diagnostic();
				$diagnostic->name       = isset($request["diagnostic"]) ? $request["diagnostic"]:"";
				$diagnostic->user_id    = isset($request["patient"]["id"]) ? $request["patient"]["id"] : $newpatient->id;
				$diagnostic->author_id  = $request["employee"]["id"];
				$diagnostic->created_by = $currentUser->id;
				$diagnostic->updated_by = $currentUser->id;
				$diagnostic->save();
			} else $diagnostic = Diagnostic::find($request["diagnostic"]["id"]);

			if (!isset($request["prescription"]["id"])){
				$prescription                = new Prescription();
				$prescription->diagnostic_id = $diagnostic->id;
				$prescription->treatment_id  = isset($request["treatment"]["id"]) ? $request["treatment"]["id"]:"";
				// $prescription->sessions      = isset($request["treatment"]["duration"]) ? $request["treatment"]["duration"]:"";
				$prescription->created_by    = $currentUser->id;
				$prescription->updated_by    = $currentUser->id;
				$prescription->save();
			} else $prescription = Prescription::find($request["prescription"]["id"]);


			$session                  = new Sessions();
			$session->prescription_id = isset($request["prescription_id"]) ? $request["prescription_id"]:$prescription->id;
			$session->treatment_id    = isset($request["treatment"]["id"]) ? $request["treatment"]["id"]:$prescription->treatment_id;
			$session->scheduled_at    = isset($request["scheduled_at"]) ? $request["scheduled_at"]:"";
			$session->room_id         = isset($request["room"]["id"]) ? $request["room"]["id"]:"";
			$session->notes           = isset($request["notes"]) ? $request["notes"]:"";
			$session->status          = isset($request["status"]) ? $request["status"]:0;

			$duration = isset($request["treatment"]["duration"]) ? (int)$request["treatment"]["duration"]:0;
			$session->scheduled_end   = isset($request["scheduled_at"]) ? date("Y-m-d H:i:s",strtotime("+$duration minutes",strtotime($request["scheduled_at"]))) : null;
			$session->save();

			//Timeline
			$prescription         = Prescription::find($session->prescription_id);
			// $diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Session is create by " . $prescription->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($session->toArray(), "Session is create successful");
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

			$session = Sessions::find($id);
			if (!$session) {
				$resp = RestResponseFactory::ok(null, "Session not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'prescription_id' => 'required|numeric',
					'room_id'         => 'required|numeric|unique:sessions,room_id,' . $session->id  . ',id,scheduled_at,' . $request["scheduled_at"],
					'scheduled_at'    => 'required'
				)
			);


			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}
			$session->prescription_id = isset($request["prescription_id"]) ? $request["prescription_id"]:$session->prescription_id;
			$session->scheduled_at    = isset($request["scheduled_at"]) ? $request["scheduled_at"]:$session->scheduled_at;
			$session->scheduled_end   = isset($request["scheduled_end"]) ? $request["scheduled_end"]:$session->scheduled_end;
			$session->room_id         = isset($request["room_id"]) ? $request["room_id"]:$session->room_id;
			$session->notes           = isset($request["notes"]) ? $request["notes"]:$session->notes;
			$session->status          = isset($request["status"]) ? $request["status"]:$session->status;

			$session->save();

			//Timeline
			$prescription         = Prescription::find($session->prescription_id);
			$diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Session is update by " . $prescription->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($session->toArray(), "Session is update successful");
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
			$session = Sessions::find($id);
			if (!$session) {
				$resp = RestResponseFactory::ok(null, "Session not found.");
				return Response::json($resp);
			}

			//Timeline
			$prescription         = Prescription::find($session->prescription_id);
			$diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Session is delete by " . $prescription->created_by_name;
			$timeline->save();

			// Delete
			$session->delete();

			$resp = RestResponseFactory::ok(null, "Session is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'sessions.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("sessions")->select(DB::raw("sessions.*, room.name AS room, diagnostic.name AS diagnostic, treatment.name AS treatment,
			CONCAT_WS(' ', CR.first_name, CR.last_name) AS created_by_name,
			CONCAT_WS(' ', UD.first_name, UD.last_name) AS updated_by_name,
			CONCAT_WS(' ', AT.first_name, AT.last_name) AS author,
			CONCAT_WS(' ', PT.first_name, PT.last_name) AS patient, PT.phone"))
			->leftJoin("room", "sessions.room_id", "=", "room.id")
			->leftJoin("prescription", "sessions.prescription_id", "=", "prescription.id")
			->leftJoin("diagnostic", "prescription.diagnostic_id", "=", "diagnostic.id")
			->leftJoin("treatment", "sessions.treatment_id", "=", "treatment.id")
			->leftJoin("users_meta as PT", "diagnostic.user_id", "=", "PT.user_id")
			->leftJoin("users_meta as AT", "diagnostic.author_id", "=", "AT.user_id")
			->leftJoin("users_meta as CR", "prescription.created_by", "=", "CR.user_id")
			->leftJoin("users_meta as UD", "prescription.updated_by", "=", "UD.user_id")
			->where('sessions.room_id', '>', 0) ;

		if ($q) {
			$query->whereRaw("CONCAT_WS(' ', PT.first_name, PT.last_name) like ?", array('%'.$q.'%'));
		}

		if (!$currentUser->isAdmin()){
			$query->where('diagnostic.author_id', $currentUser->id);
		}

		if ($week = Input::get('week')) {
			//yearweek(`date`) = yearweek(curdate())
			$query->whereRaw('YEARWEEK(sessions.scheduled_at) = YEARWEEK(?)', array($week));
		}

		if ($month = Input::get('month')) {
			// MONTH(postdate) = MONTH(CURDATE()) AND YEAR(postdate) = YEAR(CURDATE())
			$query->whereRaw('MONTH(sessions.scheduled_at) = MONTH(?) AND YEAR(sessions.scheduled_at) = YEAR(?)', array($month,$month));
		}

		if ($year = Input::get('year')) {
			// YEAR(postdate) = YEAR(CURDATE())
			$query->whereRaw('YEAR(sessions.scheduled_at) = YEAR(?)', array($year));
		}

		if ($prescription_id = Input::get('prescription_id')) {
			$query->where('sessions.prescription_id', $prescription_id);
		}

		if ($role = Input::get('role')) {
			$query->where('treatment.role', $role);
		}
		if ($user_id = Input::get('user_id')) {
			$query->where('AT.user_id', $user_id);
		}
		if ($patient_id = Input::get('patient_id')) {
			$query->where('PT.user_id', $patient_id);
		}
		if ($room_id = Input::get('room_id')) {
			$query->where('sessions.room_id', $room_id);
		}

		$query->groupBy("sessions.id");
		$query->orderBy($sort, $order);

		$session = $query->paginate($limit);
		if ($session) {
			$resp = RestResponseFactory::ok($session->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Session(s) not found.");
		}
		return Response::json($resp);
	}


	/**
	 *  @desc Get user by id
	 **/
	public function getById($id)
	{
		$session = Sessions::find($id);
		if ($session) {
			$resp = RestResponseFactory::ok($session->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Session(s) not found.");
		}
		return Response::json($resp);
	}


	public function setStatus()
	{
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;
		$resp = "";
		if ($currentUser->isAdmin()){
			$requestBody = file_get_contents('php://input');
			$request = json_decode($requestBody, true);
			// die($request);
			if (isset($request["checked"])&&is_array($request["checked"])) {
				foreach ($request["checked"] as $id) {
					$session = Sessions::find($id);
					if ($session){
						$session->status = isset($request["status"]) ? $request["status"]:0;
						$session->save();
					}
				}
				$resp = RestResponseFactory::ok(array(), "Session(s) changed status successful.");
			}
			else $resp = RestResponseFactory::ok(array(), "Session(s) not found.");
		}
		else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);

	}
}