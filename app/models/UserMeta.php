<?php

class UserMeta extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_meta';
	protected $fillable = array(
		'user_id',
		'first_name',
		'last_name',
		'phone',
		'last_login');

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function created_by()
	{
		return $this->hasOne('User');
	}

	public function updated_by()
	{
		return $this->hasOne('User');
	}
}
