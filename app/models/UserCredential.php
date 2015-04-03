<?php

class UserCredential extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_credentials';
	protected $hidden = array('password');
    protected $fillable = array('user_id', 'email', 'password', 'active');

    public function user()
    {
        return $this->belongsTo('User');
    }

}