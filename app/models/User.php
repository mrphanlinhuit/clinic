<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $softDelete = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('deleted_at');
	protected $fillable = array('group_id');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function credential()
	{
		return $this->hasOne('UserCredential');
	}

	public function meta()
	{
		return $this->hasOne('UserMeta');
	}

	public function clinic(){
		return $this->hasOne('Clinic');
	}

	public function patient(){
		return $this->hasOne('Patient');
	}

	public function group()
	{
		return $this->belongsTo('Group');
	}

	public function bond()
	{
		return $this->hasMany('Bonds');
	}

	public function tags()
	{
		return $this->hasMany('Tags');
	}

	public function message()
	{
		return $this->hasMany('Message', 'receiver');
	}

	public function roles()
	{
		return $this->belongsToMany('Role');
	}

	public function hasRole($check)
	{
		return in_array($check, array_fetch($this->roles->toArray(), 'name'));
	}

	public function getRoles()
	{
		return array_fetch($this->roles->toArray(), 'id');
	}

	public function addRole($key_roles = array())
	{
		$key_roles = is_array($key_roles)?$key_roles: array($key_roles);
		$roles = array_fetch(Role::all()->toArray(), 'id');
		$this->roles()->sync(array_intersect($roles, $key_roles));
	}


	public function isAdmin($array = array('admin')) {
		return $this->hasRole('admin');
	}

	public function isMod($array = array('admin', 'manager')) {
		return in_array($this->group->name, $array);
	}

	public function isOwner($id = 0) {
		return (($id === $this->id)||$this->isMod||$this->isAdmin);
	}

	public function fullName() {
		$fullName = array();
		if ($this->meta->first_name != "") {
			$fullName[] = $this->meta->first_name;
		}
		if ($this->meta->last_name != "") {
			$fullName[] = $this->meta->last_name;
		}

		return implode(" ", $fullName);
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}
}
