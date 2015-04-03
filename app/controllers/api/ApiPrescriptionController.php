<?php

class ApiPrescriptionController extends BaseApiController {

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
					'sessions'      => 'integer|min:1',
					'diagnostic_id' => 'integer|min:1',
					'treatment_id'  => 'integer|min:1',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$prescription                = new Prescription();
			$prescription->notes         = isset($request["notes"])?$request["notes"]:"";
			$prescription->diagnostic_id = isset($request["diagnostic_id"]) ? $request["diagnostic_id"] :"";
			$prescription->treatment_id  = isset($request["treatment_id"]) ? $request["treatment_id"] :"";
			$prescription->sessions      = $request["sessions"];
			$prescription->created_by    = $currentUser->id;
			$prescription->updated_by    = $currentUser->id;
			$prescription->save();

			// if ((int)$request["sessions"]) {
			// 	$session                  = new Sessions();
			// 	$session->prescription_id = $prescription->id;
			// 	$session->treatment_id    = isset($request["treatment_id"])?$request["treatment_id"]:"";
			// 	$session->room_id         = isset($request["room_id"])?$request["room_id"]:"";
			// 	$session->notes           = isset($request["notes"])?$request["notes"]:"";
			// 	$session->status          = isset($request["status"])?$request["status"]:"";
			// 	$session->save();
			// }

			$prescription->treatment       = Treatment::find($prescription->treatment_id)->name;
			
			$prescription->received        = $prescription->scheduled = 0;
			$prescription->updated_by_name = User::find($prescription->updated_by)->fullName();
			$prescription->created_by_name = User::find($prescription->created_by)->fullName();


			// Timeline
			$diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Prescription is create by " . $prescription->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($prescription->toArray(), "Prescription is create successful");
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

			$prescription = Prescription::find($id);
			if (!$prescription) {
				$resp = RestResponseFactory::ok(null, "Prescription not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'sessions'      => 'integer',
					'diagnostic_id' => 'integer',
					'treatment_id'  => 'integer',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors)."</ul>");
				return Response::json($resp);
			}

			$prescription->sessions      = $request["sessions"];
			$prescription->notes         = isset($request["notes"])?$request["notes"]:$prescription->notes;
			$prescription->diagnostic_id = isset($request["diagnostic_id"])?$request["diagnostic_id"]:$prescription->diagnostic_id;
			$prescription->treatment_id  = isset($request["treatment_id"])?$request["treatment_id"]:$prescription->treatment_id;

			$prescription->updated_by    = $currentUser->id;
			$prescription->save();

			$prescription->updated_by_name = User::find($prescription->updated_by)->fullName();
			$prescription->created_by_name = User::find($prescription->created_by)->fullName();

			//Timeline
			$diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Prescription is update by " . $prescription->created_by_name;
			$timeline->save();


			$resp = RestResponseFactory::ok($prescription->toArray(), "Prescription is update successful");
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
			$prescription = Prescription::find($id);
			if (!$prescription) {
				$resp = RestResponseFactory::ok(null, "Prescription not found.");
				return Response::json($resp);
			}

			//Timeline
			$diagnostic           = Diagnostic::find($prescription->diagnostic_id);
			$timeline             = new Timeline();
			$timeline->user_id    = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note       = "Prescription is update by " . $prescription->created_by_name;
			$timeline->save();

			$prescription->delete();
			$resp = RestResponseFactory::ok(null, "Prescription is delete successful");
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
		$sort  = Input::get('sort') ? Input::get('sort') : 'prescription.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q     = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		$query = DB::table("prescription")
			->select(DB::raw("prescription.*, treatment.name AS treatment, treatment.id AS treatment_id, CONCAT_WS(' ', CR.first_name, CR.last_name) AS created_by_name, CONCAT_WS(' ', UD.first_name, UD.last_name) AS updated_by_name,
			  (SELECT COUNT(s.id) FROM sessions AS s LEFT JOIN prescription AS p ON p.id = s.prescription_id
			  	WHERE p.id = sessions.prescription_id) AS scheduled,
			  (SELECT COUNT(s.id) FROM sessions AS s LEFT JOIN prescription AS p ON p.id = s.prescription_id
			  	WHERE s.status = '1' AND p.id = sessions.prescription_id) AS received
			"))
			->leftJoin("diagnostic", "prescription.diagnostic_id", "=", "diagnostic.id")
			->leftJoin("sessions", "sessions.prescription_id", "=", "prescription.id")
			->leftJoin("treatment", "prescription.treatment_id", "=", "treatment.id")
			->leftJoin("users_meta as CR", "prescription.created_by", "=", "CR.user_id")
			->leftJoin("users_meta as UD", "prescription.updated_by", "=", "UD.user_id");

		if ($q) {
			$query->whereRaw("prescription.notes LIKE ?", array('%'.$q.'%'));
		}

		if ($user_id = Input::get('user_id')) {
			$query->where('diagnostic.user_id', $user_id);
		}
		if ($diagnostic_id = Input::get('diagnostic_id')) {
			$query->where('prescription.diagnostic_id', $diagnostic_id);
		}

		$query->groupBy("prescription.id");
		$query->orderBy($sort, $order);

		$prescription = $query->paginate($limit);
		if ($prescription) {
			$resp = RestResponseFactory::ok($prescription->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Prescription(s) not found.");
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
			$prescription = Prescription::find($id);
		//}
		if ($prescription) {
			$today                         = time();
			$sessions                      = Sessions::where("prescription_id", "=", $id);
			$prescription->received        = $sessions->where("status", true)->count();
			$prescription->scheduled       = $sessions->where("scheduled_at", ">", date("Y-m-d H:i:s", $today))->count();
			$prescription->updated_by_name = User::find($prescription->updated_by)->fullName();
			$prescription->created_by_name = User::find($prescription->created_by)->fullName();
			$resp = RestResponseFactory::ok($prescription->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Prescription(s) not found.");
		}
		return Response::json($resp);
	}
}