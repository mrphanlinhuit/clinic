<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timeline', function(Blueprint $table)
		{
			//
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('created_by')->unsigned();
			$table->string("note", 256);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('timeline');
	}

}
