<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSession extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('bondtype');
		Schema::create('bondtype', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100);
			$table->float("tax", 10);
			$table->float('price');
			$table->integer('session');
			$table->integer('timetouse');
			$table->boolean('status');
			$table->timestamps();
		});

		Schema::dropIfExists('bonds');
		Schema::create('bonds', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('bondtype_id')->unsigned();
			$table->integer('payment_id')->unsigned();
			$table->timestamp('expired_at')->nullable();
			$table->date('activated_at')->nullable();
			$table->integer('session')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('bondtype_id')->references('id')->on('bondtype');
			$table->foreign('user_id')->references('id')->on('users');
		});


		Schema::dropIfExists('sessions');
		Schema::create('sessions', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('prescription_id')->unsigned()->nullable();
			$table->integer('treatment_id')->unsigned()->nullable();
			$table->integer('bond_id')->unsigned()->nullable();
			$table->integer('room_id')->unsigned()->nullable();
			$table->integer('giveby_id')->unsigned()->nullable();
			$table->integer('payment_id')->unsigned()->nullable();
			$table->tinyInteger('status')->default('0');
			$table->text('notes');
			$table->timestamp('scheduled_at')->nullable();
			$table->timestamp('scheduled_end')->nullable();
			$table->timestamps();

			$table->foreign('prescription_id')->references('id')->on('prescription')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sessions');
		Schema::dropIfExists('bonds');
		Schema::dropIfExists('bondtype');
	}

}
