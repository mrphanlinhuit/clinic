<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnostic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		Schema::dropIfExists('pathology');
		Schema::create('pathology', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100)->unique();
			$table->text('prescription');
			$table->timestamps();
		});


		Schema::dropIfExists('referer');
		Schema::create('referer', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100)->unique();
			$table->boolean('status');
			$table->timestamps();
		});

		Schema::dropIfExists('diagnostic');
		Schema::create('diagnostic', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('referer_id')->unsigned()->nullable();
			$table->integer('pathology_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('author_id')->unsigned();

			$table->string('name', 100);
			$table->text('anamnesis');
			$table->text('background');
			$table->text('description');
			$table->string('close', 32);

			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->timestamps();

			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('author_id')->references('id')->on('users');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::table('attachments', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->integer('diagnostic_id')->unsigned()->nullable();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::dropIfExists('diagnostic_review');
		Schema::create('diagnostic_review', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('diagnostic_id')->unsigned();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->text('description');
			$table->timestamps();

			$table->foreign('diagnostic_id')->references('id')->on('diagnostic')->onDelete('cascade');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('attachments', function(Blueprint $table)
		{
			$table->dropForeign('attachments_diagnostic_id_foreign');
			$table->dropColumn('diagnostic_id');
		});
		Schema::dropIfExists('diagnostic_review');
		Schema::dropIfExists('diagnostic');
		Schema::dropIfExists('referer');
		Schema::dropIfExists('pathology');

	}

}
