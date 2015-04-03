<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersId2UsersTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_tokens', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_tokens', function(Blueprint $table)
		{
			$table->dropForeign('users_tokens_user_id_foreign');
			$table->dropColumn('user_id');
		});
	}

}
