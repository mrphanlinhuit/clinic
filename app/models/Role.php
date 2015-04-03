<?php

class Role extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_role';
	protected $fillable = array('name');

	public function users()
    {
        return $this->belongsToMany('User');
    }

}