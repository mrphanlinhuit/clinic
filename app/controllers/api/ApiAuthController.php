<?php

class ApiAuthController extends BaseApiController {

	/**
	 *  @desc Login
	 **/
	public
	function login() {
		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		if ($authToken = AuthToken::login($request->username, $request->password)) {
			$resp = RestResponseFactory::ok($authToken->toArray());
		} else {
			$resp = RestResponseFactory::badrequest(null, "Invalid Username/Password");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc Logout
	 **/
	public
	function logout() {
		$authToken = App::make('authToken');
		$authToken->expired_at = date('Y-m-d H:i:s');
		$authToken->updated_at = $authToken->expired_at;
		$authToken->save();
		$resp = RestResponseFactory::ok(null);
		return Response::json($resp);
	}
}