<?php

class Room extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'room';
	protected $fillable = array('name');
}
