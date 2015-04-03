
<?php

class Attachments extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'attachments';
	protected $fillable = array('path', 'filetype', 'description');

	public function users()
	{
		return $this->hasMany('User');
	}

	public function diagnostic()
	{
		return $this->belongsTo('Diagnostic');
	}

}

