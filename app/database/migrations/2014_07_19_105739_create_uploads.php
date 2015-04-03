<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploads extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('attachments');
		Schema::create('attachments', function(Blueprint $table)
		{
			$table->engine = 'InnoDb';
			$table->increments('id')->unsigned();
			$table->string('hash', 100)->unique();
			$table->string('filepath', 255);
			$table->string('filetype', 255);
			$table->string('description', 255);
			$table->string('original_filename', 255);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('attachments');
	}

}
