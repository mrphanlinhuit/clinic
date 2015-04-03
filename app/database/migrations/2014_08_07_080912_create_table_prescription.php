<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePrescription extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('prescription');
		Schema::create('prescription', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('diagnostic_id')->unsigned();
			$table->integer('treatment_id')->unsigned();		
			$table->integer('sessions')->unsigned();
			$table->text('notes');
			
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->timestamps();

			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('diagnostic_id')->references('id')->on('diagnostic')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('prescription');
	}

}
