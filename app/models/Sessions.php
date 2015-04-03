<?php

class Sessions extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sessions';
	protected $fillable = array('name');

	public function prescription()
	{
		return $this->belongsTo('Prescription');
	}

}