<?php

class AuthToken extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_tokens';

	protected $fillable = array('id');
	public $incrementing = false;

	public static function loginAsUser($id)
	{
		$user = User::find($id);

		if ($user) {
			$authToken = new AuthToken();
			$authToken->id = md5(time() + $user->email + $user->password);
			$authToken->user_id = $user->id;
			$authToken->data = serialize(array());
			$authToken->expired_at = date('Y-m-d H:i:s', strtotime("+ 2 hours"));
			$authToken->save();

			return $authToken;
		} else {
			return null;
		}
	}

	public static function login($user_email, $password)
	{
		$userCredential = UserCredential::where('email', $user_email)->first();

		if ($userCredential && Hash::check($password, $userCredential->password)) {
			$authToken = new AuthToken();
			$authToken->id = md5(time() + $user_email + $password);
			$authToken->user_id = $userCredential->user_id;
			$authToken->data = serialize(array());
			$authToken->expired_at = date('Y-m-d H:i:s', strtotime("+ 2 hours"));
			$authToken->save();

			return $authToken;
		} else {
			return null;
		}
	}



	public function user() {
		return $this->belongsTo('User');
	}

	public function isExpired() {
		$now = time();
		$expired_at = strtotime($this->expired_at);
		if ($now > $expired_at) {
			return true;
		}

		return false;
	}

	public function extendExpiry() {
		$this->expired_at = date('Y-m-d H:i:s', strtotime("+ 24 hours"));
		$this->save();
	}

    public static function facebookLogin($facebook_id)
    {
        $userFacebookCredential = UserFacebookCredential::where('facebook_id', $facebook_id)
            ->first();

        if ($userFacebookCredential) {
            $authToken = new AuthToken();
            $authToken->id = md5(time() + $userFacebookCredential->access_token);
            $authToken->user_id = $userFacebookCredential->user_id;
            $authToken->data = serialize(array());
            $authToken->expired_at = date('Y-m-d H:i:s', strtotime("+ 2 hours"));
            $authToken->save();


            return $authToken;
        } else {
            return null;
        }
    }

    public static function twitterLogin($twitter_id)
    {
        $userTwitterCredential = UserTwitterCredential::where('twitter_id', $twitter_id)
            ->first();

        if ($userTwitterCredential) {
            $authToken = new AuthToken();
            $authToken->id = md5(time() + $userTwitterCredential->access_token);
            $authToken->user_id = $userTwitterCredential->user_id;
            $authToken->data = serialize(array());
            $authToken->expired_at = date('Y-m-d H:i:s', strtotime("+ 2 hours"));
            $authToken->save();


            return $authToken;
        } else {
            return null;
        }
    }
}