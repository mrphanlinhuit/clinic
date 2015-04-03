<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTreatment extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('treatment', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100);
			$table->string('role', 100);
			$table->integer('duration');
			$table->float("tax", 10);
			$table->float('price');
			$table->boolean('active');
			$table->text('description');
			$table->timestamps();
		});

		Schema::table('sessions', function(Blueprint $table)
		{
			$table->foreign('treatment_id')->references('id')->on('treatment');
		});
		Schema::table('prescription', function(Blueprint $table)
		{
			$table->foreign('treatment_id')->references('id')->on('treatment');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sessions', function(Blueprint $table)
		{
			$table->dropForeign('prescription_treatment_id_foreign');
			$table->dropForeign('sessions_treatment_id_foreign');
			$table->dropColumn('treatment_id');
		});
		Schema::dropIfExists('treatment');
	}

}
