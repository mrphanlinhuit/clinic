<?php

class ApiDiagnosticController extends BaseApiController {
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
					'name'         => 'required',
					'user_id'      => 'required|numeric',
					'author_id'    => 'required|numeric',
					// 'referer_id'   => 'required|numeric',
					// 'pathology_id' => 'required|numeric',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors).
					"</ul>");
				return Response::json($resp);
			}

			$diagnostic = new Diagnostic();
			$diagnostic->name = $request["name"];
			$diagnostic->user_id = isset($request["user_id"]) ? $request["user_id"] : "";
			$diagnostic->pathology_id = isset($request["pathology_id"]) ? $request["pathology_id"] : "";
			$diagnostic->referer_id = isset($request["referer_id"]) ? $request["referer_id"] : "";
			$diagnostic->author_id = isset($request["author_id"]) ? $request["author_id"] : "";
			$diagnostic->anamnesis = isset($request["anamnesis"]) ? $request["anamnesis"] : "";
			$diagnostic->background = isset($request["background"]) ? $request["background"] : "";
			$diagnostic->description = isset($request["description"]) ? $request["description"] : "";
			$diagnostic->close = isset($request["close"]) ? $request["close"] : "";
			$diagnostic->created_by = $currentUser->id;
			$diagnostic->updated_by = $currentUser->id;
			$diagnostic->save();

			if (isset($request['tags'])) {
				foreach ($request['tags'] as $tag) {
					$diagnosticTag                = new Tags();
					$diagnosticTag->diagnostic_id = $diagnostic->id;
					$diagnosticTag->name          = $tag["name"];
					$diagnosticTag->save();
				}
			}



			$diagnostic->updated_by_name = User::find($diagnostic->updated_by)->fullName();
			$diagnostic->created_by_name = User::find($diagnostic->created_by)->fullName();
			//Timeline
			$timeline = new Timeline();
			$timeline->user_id = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note = "Diagnostic is create by ".$diagnostic->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($diagnostic->toArray(), "Diagnostic is create successful");
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

			$diagnostic = Diagnostic::find($id);
			if (!$diagnostic) {
				$resp = RestResponseFactory::ok(null, "Diagnostic not found.");
				return Response::json($resp);
			}

			$errors = array();
			$validator = Validator::make(
				$request,
				array(
					'name' => 'required',
					'user_id' => 'required|numeric',
					'author_id' => 'required|numeric',
					// 'referer_id'   => 'required|numeric',
					// 'pathology_id' => 'required|numeric',
				)
			);
			if ($validator->fails()) $errors = array_merge($errors, $validator->messages()->all('<li>:message</li>'));
			if (count($errors) > 0) {
				$resp = RestResponseFactory::badrequest(null, "<ul>".implode("", $errors).
					"</ul>");
				return Response::json($resp);
			}
			$diagnostic->name = $request["name"];
			$diagnostic->user_id = isset($request["user_id"]) ? $request["user_id"] : $diagnostic->user_id;
			$diagnostic->pathology_id = isset($request["pathology_id"]) ? $request["pathology_id"] : $diagnostic->pathology_id;
			$diagnostic->referer_id = isset($request["referer_id"]) ? $request["referer_id"] : $diagnostic->referer_id;
			$diagnostic->author_id = isset($request["author_id"]) ? $request["author_id"] : $diagnostic->author_id;
			$diagnostic->anamnesis = isset($request["anamnesis"]) ? $request["anamnesis"] : $diagnostic->anamnesis;
			$diagnostic->background = isset($request["background"]) ? $request["background"] : $diagnostic->background;
			$diagnostic->description = isset($request["description"]) ? $request["description"] : $diagnostic->description;
			$diagnostic->close = isset($request["close"]) ? $request["close"] : $diagnostic->close;
			$diagnostic->updated_by = $currentUser->id;
			$diagnostic->save();

			if (isset($request['tags'])) {
				$tags = $request['tags'];
				// delete all old tags
				$_tags = Tags::where('diagnostic_id', '=', $diagnostic->id)->get();
				if ($_tags) foreach ($_tags as $d) $d->delete();
				foreach ($tags as $tag) {
					$diagnosticTag                = new Tags();
					$diagnosticTag->diagnostic_id = $diagnostic->id;
					$diagnosticTag->name          = $tag["name"];
					$diagnosticTag->save();
				}
			}

			$diagnostic->tags;
			$diagnostic->updated_by_name = User::find($diagnostic->updated_by)->fullName();
			$diagnostic->created_by_name = User::find($diagnostic->created_by)->fullName();

			//Timeline
			$timeline = new Timeline();
			$timeline->user_id = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note = "Diagnostic is update by ".$diagnostic->created_by_name;
			$timeline->save();

			$resp = RestResponseFactory::ok($diagnostic->toArray(), "Diagnostic is update successful");
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
			$diagnostic = Diagnostic::find($id);
			if (!$diagnostic) {
				$resp = RestResponseFactory::ok(null, "Diagnostic not found.");
				return Response::json($resp);
			}

			//Timeline
			$timeline = new Timeline();
			$timeline->user_id = $diagnostic->user_id;
			$timeline->created_by = $currentUser->id;
			$timeline->note = "Diagnostic is delete by ".$currentUser->fullName();
			$timeline->save();

			$diagnostic->delete();
			$resp = RestResponseFactory::ok(null, "Diagnostic is delete successful");
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
		$sort = Input::get('sort') ? Input::get('sort') : 'diagnostic.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;
		//$uri = Request::path();

		// stats query
		$query = DB::table("diagnostic")->select(DB::raw("diagnostic.*, prescription.sessions, referer.name AS referer, pathology.name AS pathology
				,CONCAT_WS(' ', users_meta.first_name, users_meta.last_name) as author,
			(SELECT SUM(prescription.sessions) FROM prescription WHERE prescription.diagnostic_id = diagnostic.id) AS sessions,
			(SELECT COUNT(s.id) FROM sessions AS s LEFT JOIN prescription AS p ON p.id = s.prescription_id
				WHERE s.scheduled_at != '0000-00-00 00:00:00' AND p.diagnostic_id = diagnostic.id) AS scheduled,
			(SELECT COUNT(s.id) FROM sessions AS s LEFT JOIN prescription AS p ON p.id = s.prescription_id
				WHERE s.status = 1 AND p.diagnostic_id = diagnostic.id) AS received"))
			->leftJoin("referer", "diagnostic.referer_id", "=", "referer.id")
			->leftJoin("pathology", "diagnostic.pathology_id", "=", "pathology.id")
			->leftJoin("users_meta", "diagnostic.author_id", "=", "users_meta.user_id")
			->leftJoin("prescription", "prescription.diagnostic_id", "=", "diagnostic.id")
			->leftJoin("sessions", "sessions.prescription_id", "=", "prescription.id");

		if ($user_id = Input::get('uid')) {
			$query->where('diagnostic.user_id', $user_id);
		}

		if ($q) {
			$query->whereRaw("diagnostic.name LIKE ?", array('%'.$q.'%'));
		}

		$query->groupBy("diagnostic.id");
		$query->orderBy($sort, $order);

		$diagnostic = $query->paginate($limit);
		if ($diagnostic) {
			$resp = RestResponseFactory::ok($diagnostic->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Diagnostic(s) not found.");
		}
		return Response::json($resp);
	}


	public
	function getById($id) {
		//$authToken = App::make('authToken');
		//if ($authToken->user->isAdmin()) {
		$diagnostic = Diagnostic::find($id);
		//}
		if ($diagnostic) {
			$diagnostic->tags;
			$diagnostic->patient_name = User::find($diagnostic->user_id)->fullName();
			$diagnostic->updated_by_name = User::find($diagnostic->updated_by)->fullName();
			$diagnostic->created_by_name = User::find($diagnostic->created_by)->fullName();
			$resp = RestResponseFactory::ok($diagnostic->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Diagnostic(s) not found.");
		}
		return Response::json($resp);
	}
}