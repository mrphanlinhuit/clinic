<?php

class Message extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = "users_message";
	//protected $fillable = array("id", "topic", "body");

	public function senders()
	{
		return $this->hasOne("UserMeta", "user_id", "sender");
	}

	public function receivers()
	{
		return $this->hasOne("UserMeta", "user_id", "receiver");
	}

}
