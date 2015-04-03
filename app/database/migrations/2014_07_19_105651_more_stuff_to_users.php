<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoreStuffToUsers extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('users_meta', function(Blueprint $table)
		{
			$table->date('birthday')->nullable();
			$table->enum('gender', array('male', 'female'))->default('male');
			$table->string('referer', 64)->nullable();
			//
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('users_meta', function(Blueprint $table)
		{
			$table->dropColumn('birthday');
			$table->dropColumn('gender');
			$table->dropColumn('referer');
			//
		});
	}
}
