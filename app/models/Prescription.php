<?php

class Prescription extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'prescription';
	protected $fillable = array('name');

	public function treatment()
	{
		return $this->belongsTo('Treatment');
	}

	public function diagnostic()
	{
		return $this->belongsTo('Diagnostic');
	}
	
}

