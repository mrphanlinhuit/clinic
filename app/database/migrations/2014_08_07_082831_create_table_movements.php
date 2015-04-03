<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMovements extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('movements');
		Schema::create('movements', function(Blueprint $table)
		{
			//
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->float("amount", 10);
			$table->string("note", 255);
			$table->integer('bond_id')->unsigned()->nullable();;
			$table->integer('movements_id')->unsigned()->nullable();
			$table->integer('invoices_sent_id')->unsigned()->nullable();
			$table->integer('invoices_providers_id')->unsigned()->nullable();
			$table->enum('payment_type', array('money', 'card', 'bond'))->default('money');
			$table->integer('created_by')->unsigned();
			$table->timestamps();

			// $table->foreign('movements_id')->references('id')->on('movements')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('movements');
	}

}
