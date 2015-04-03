<?php

class ReceiveInvoices extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'receive_invoices';
	protected $fillable = array('name');

	public function provider()
	{
		return $this->hasMany('Provider');
	}

}

