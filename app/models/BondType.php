<?php

class BondType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bondtype';
	protected $fillable = array('name');

	public function diagnostic()
	{
		return $this->hasMany('Diagnostic');
	}

}
