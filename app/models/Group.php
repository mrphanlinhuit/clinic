<?php

class Group extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_group';
	protected $fillable = array('id', 'name', 'description');

	public function users()
	{
		return $this->hasMany('User');
	}

}

