<?php

class InvoicesSent extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'invoices_sent';
	protected $fillable = array('name');

	
	public function movements()
	{
		return $this->hasMany('Movements');
	}
}
