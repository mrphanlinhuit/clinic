<?php

class Bonds extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bonds';
	protected $fillable = array('name');

	public function bondtype()
	{
		return $this->hasMany('Diagnostic');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}
}
