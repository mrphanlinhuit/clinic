
<?php

class Diagnostic extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'diagnostic';
	protected $fillable = array('name');

	public function attachments()
	{
		return $this->hasMany('Attachments');
	}
	public function tags()
	{
		return $this->hasMany('Tags');
	}
}

