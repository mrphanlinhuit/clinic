<?php

class Referer extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'referer';
	protected $fillable = array('name');

	public function diagnostic()
	{
		return $this->hasMany('Diagnostic');
	}

}
