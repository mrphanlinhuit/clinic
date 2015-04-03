<?php
use Aws\ S3\ S3Client;

class ApiUploadController extends BaseApiController {

	public
	function delete($hash) {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		if ($currentUser) {
			$attachment = Attachments::where('hash', $hash)->first();
			if ($attachment) {
				$attachment->delete();
				$unlink = public_path("uploads/".$attachment->user_id."/$hash.".$attachment->filetype);@
				unlink($unlink);

				// Timeline
				$timeline = new Timeline();
				$timeline->user_id = $attachment->user_id;
				$timeline->created_by = $currentUser->id;
				$timeline->note = "Attachment is delete by ".$attachment->user_id.
				"/$hash.".$attachment->filetype;
				$timeline->save();

				$resp = RestResponseFactory::ok(array("unlink" => $unlink));
			} else {
				$resp = RestResponseFactory::notfound(array(), "Attachment(s) not found.");
			}
			return Response::json($resp);
		} else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}

	public
	function getById($id){
		$attachment = Attachments::find($id);
		if ($attachment) {
			$resp = RestResponseFactory::ok($attachment->toArray());
		} else {
			$resp = RestResponseFactory::ok(array(), "Attachment(s) not found.");
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

			$attachment = Attachments::find($id);
			if (!$attachment) {
				$resp = RestResponseFactory::ok(null, "Attachments not found.");
				return Response::json($resp);
			}

			$attachment->description = isset($request["description"]) ? $request["description"] : $attachment->description;
			$attachment->save();

			$resp = RestResponseFactory::ok($attachment->toArray(), "Attachments is update successful");
		} else {
			$resp = RestResponseFactory::ok(null, "User not permission.");
		}
		return Response::json($resp);
	}


	public
	function search() {
		$authToken = App::make('authToken');
		$currentUser = $authToken->user;

		$limit = Input::get('limit') ? (int) Input::get('limit') : Config::get('restful.defaults.pagination.limit');
		$sort = Input::get('sort') ? Input::get('sort') : 'attachments.id';
		$order = Input::get('order') ? Input::get('order') : 'asc';
		$q = Input::get('q') ? Input::get('q') : null;

		if ($currentUser) {
			$query = DB::table("attachments")->select(DB::raw("attachments.*"));
			//->leftJoin("diagnostic", "diagnostic.id", "=", "attachments.diagnostic_id");
			//->leftJoin("users_meta", "diagnostic.user_id", "=", "users_meta.user_id");

			if ($user_id = Input::get('uid')) {
				$query->where('attachments.user_id', $user_id);
			}

			if ($diagnostic_id = Input::get('did')) {
				$query->where('attachments.diagnostic_id', $diagnostic_id);
			}

			if ($q) {
				$query->whereRaw("attachments.description LIKE ?", array('%'.$q.'%'));
			}

			$query->groupBy("attachments.id");
			$query->orderBy($sort, $order);

			$attachments = $query->paginate($limit);
			if ($attachments) {
				$resp = RestResponseFactory::ok($attachments->toArray());
			} else {
				$resp = RestResponseFactory::ok(array(), "Attachment(s) not found.");
			}
			return Response::json($resp);
		} else {
			$resp = RestResponseFactory::forbidden(null, "User not permission.");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc Upload S3 Amazon
	 **/
	public
	function s3_upload() {
		$authToken = App::make('authToken');
		$user_id = $authToken->user_id;
		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		if (Input::hasFile('file')) {
			$name = $user_id.
			'_'.time().
			'_'.Input::file('file')->getClientOriginalName();
			$extension = Input::file('file')->getClientOriginalExtension();

			// upload aws
			$s3 = S3Client::factory(Config::get('thirdparty.'.App::environment().
				'.aws.keys'));
			// Upload an object by streaming the contents of a file
			// $pathToFile should be absolute path to a file on disk
			$result = $s3->putObject(array(
				'Bucket' => Config::get('thirdparty.'.App::environment().'.aws.s3_bucket'),
				'Key' => $name,
				'Body' => file_get_contents($_FILES['file']['tmp_name']),
				'ACL' => 'public-read',
				'Metadata' => array()
			));
			/*
			$result = $s3->waitUntilObjectExists(array(
				'Bucket' => Config::get('thirdparty.' . App::environment() . '.aws.s3_bucket'),
				'Key'    => $name
			));
			*/

			$resp = RestResponseProvider::ok(array(
				'url' => $result['ObjectURL']
			));
		} else {
			$resp = RestResponseFactory::badrequest(null, "No file found");
		}
		return Response::json($resp);
	}

	/**
	 *  @desc Upload
	 **/
	public
	function upload() {
		$authToken = App::make('authToken');
		$user_id = Input::get('uid') ? Input::get('uid') : $authToken->user_id;
		$diagnostic_id = Input::get('did') ? Input::get('did') : null;
		$invoices_providers_id = Input::get('pid') ? Input::get('pid') : null;

		if (Input::hasFile('file')) {
			$ext = Input::file('file')->getClientOriginalExtension();
			$originalname = Input::file('file')->getClientOriginalName();
			$hash = md5($user_id.'_'.time().'_'.$originalname);
			//$name = preg_replace('/[^A-Za-z0-9.]/', '', $name)

			$destinationPath = public_path('uploads/'.$user_id);
			if (!is_dir($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}

			$fn = Input::file('file')->move($destinationPath, "$hash.$ext");
			$url = url("uploads/$user_id/$hash.$ext");

			$attachment = new Attachments();
			$attachment->user_id = $user_id;
			$attachment->filepath = $url;
			$attachment->filetype = $ext;
			$attachment->description = $attachment->original_filename = $originalname;
			$attachment->hash = $hash;
			// if ($invoices_providers_id) {
			// 	$attachment->invoices_providers_id = $invoices_providers_id;
			// }
			if ($diagnostic_id) {
				$attachment->diagnostic_id = $diagnostic_id;
			}
			$attachment->save();

			// Timeline
			$timeline = new Timeline();
			$timeline->user_id = $attachment->user_id;
			$timeline->created_by = $authToken->user_id;
			$timeline->note = "Attachment is upload by ".$attachment->user_id."/$hash.".$attachment->filetype;
			$timeline->save();

			$resp = RestResponseFactory::ok(array(
				'id'          => $attachment->id,
				'url'         => $url,
				'description' => $originalname,
				'hash'        => $hash
			));
		} else {
			$resp = RestResponseFactory::badrequest(null, "No file found");
		}
		return Response::json($resp);
	}

	public
	function avatar() {
		$authToken = App::make('authToken');
		$user_id = $authToken->user_id;
		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		if (Input::hasFile('file')) {
			$name = $user_id.
			'_'.time().
			'_'.Input::file('file')->getClientOriginalName();
			//$name = preg_replace('/[^A-Za-z0-9.]/', '', $name)
			$extension = Input::file('file')->getClientOriginalExtension();

			$destinationPath = public_path('uploads/'.$user_id);
			if (!is_dir($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			$fn = Input::file('file')->move($destinationPath, $name);

			$url = url('uploads/'.$user_id.'/'.$name);
			$resp = RestResponseFactory::ok(array(
				'url' => $url
			));
		} else {
			$resp = RestResponseFactory::badrequest(null, "No file found");
		}
		return Response::json($resp);
	}
	/**
	 *  @desc Upload
	 **/
	public
	function secureUpload() {
		$authToken = App::make('authToken');
		$user_id = $authToken->user_id;

		$requestBody = file_get_contents('php://input');
		$request = json_decode($requestBody);

		if (Input::hasFile('file')) {
			$name = Input::file('file')->getClientOriginalName();
			$extension = Input::file('file')->getClientOriginalExtension();

			$destinationPath = storage_path('uploads/'.$user_id);
			if (!is_dir($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}

			//save in db
			$hash = sha1($user_id.$name.time());
			$upload = new Upload();
			$upload->hash = $hash;
			$upload->filepath = $destinationPath;
			$upload->original_filename = $name;
			$upload->save();

			$fn = Input::file('file')->move($destinationPath, $hash);

			$url = url($destinationPath.'/'.$name);
			$resp = RestResponseFactory::ok($upload->toArray());
		} else {
			$resp = RestResponseFactory::badrequest(null, "No file found");
		}
		return Response::json($resp);
	}

	public
	function secureDownload() {
		$authToken = App::make('authToken');
		$user_id = $authToken->user_id;
		$currentUser = $authToken->user;

		$hash = Input::get("hash");
		$resp = RestResponseFactory::badrequest(null, "Invalid Request");
		if (!empty($hash)) {
			$upload = Upload::where("hash", "=", $hash)->first();
			if (!empty($upload)) {
				$file = $upload->filepath.'/'.$upload->hash;
				$filename = $upload->original_filename;
				return Response::download($file, $filename, array());
			}
		}
		return Response::json($resp);
	}
}