<?php

class Pathology extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pathology';
	protected $fillable = array('name');

	public function diagnostic()
	{
		return $this->hasMany('Diagnostic');
	}

}

