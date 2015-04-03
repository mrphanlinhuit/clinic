<?php

class Provider extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'providers';
	protected $fillable = array('name');
}

