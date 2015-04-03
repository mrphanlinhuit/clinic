<?php

class Timeline extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'timeline';
	protected $fillable = array();

	public function user()
	{
		return $this->hasMany('User');
	}

}
