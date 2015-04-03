<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::dropIfExists('users_role');
		Schema::create('users_role', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('name', 100)->unique();
			$table->string('description', 100);
			$table->timestamps();
		});


		Schema::dropIfExists('users_group');
		Schema::create('users_group', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 32)->unique();
			$table->string('description', 64);
			$table->enum('status', array('pending', 'active', 'inactive', 'banned'))->default('active');
			$table->timestamps();
		});


		Schema::dropIfExists('users');
		Schema::create('users', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('group_id')->unsigned();
			$table->enum('status', array('pending', 'active', 'inactive', 'banned'))->default('pending');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('group_id')->references('id')->on('users_group');
		});


		Schema::dropIfExists('users_meta');
		Schema::create('users_meta', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')->unsigned();

			$table->string('first_name', 64);
			$table->string('last_name', 64);
			$table->string('alias', 64);
			$table->string('phone', 16);
			$table->string('mobile', 16);
			$table->string('national_id', 100);
			$table->string('professional_number', 100);
			
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
		});

		Schema::dropIfExists('clinic');
		Schema::create('clinic', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->time('openhour1')->nullable();
			$table->time('openhour2')->nullable();
			$table->time('closehour1')->nullable();
			$table->time('closehour2')->nullable();
			$table->string('vat_id', 100);
			$table->text('notes');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::dropIfExists('users_message');
		Schema::create('users_message', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('sender')->unsigned();
			$table->integer('receiver')->unsigned();

			$table->string('topic', 100);
			$table->string('body', 1000);
			$table->timestamp('read_at')->nullable()->default(NULL);
			$table->timestamps();

			$table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('receiver')->references('id')->on('users')->onDelete('cascade');
		});


		Schema::dropIfExists('patient');
		Schema::create('patient', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('note');
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

		Schema::table('users', function(Blueprint $table)
		{
			$table->dropForeign('users_group_id_foreign');
			$table->dropColumn('group_id');
		});
		Schema::dropIfExists('users_group');
		Schema::dropIfExists('users_message');
		Schema::dropIfExists('users_role');
		Schema::dropIfExists('users');
		Schema::dropIfExists('patient');
	}

}
