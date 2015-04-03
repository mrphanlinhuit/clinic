<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProviderInvoices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('providers');
		Schema::create('providers', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100);
			$table->string('address', 100);
			$table->string('email', 100);
			$table->string('fax', 16);
			$table->string('phone', 16);
			$table->string('mobile', 16);
			$table->string('fiscalcode', 100);
			$table->string("city", 32);
			$table->string("province", 32);
			$table->string("postal", 16);
			$table->string("country", 32);
			$table->text("note");
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->timestamps();
		});


		Schema::dropIfExists('invoices_providers');
		Schema::create('invoices_providers', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->integer('provider_id')->unsigned();
			$table->integer('attachment_id')->unsigned();
			$table->timestamp('date');
			$table->string('name', 100);
			$table->string('number', 64);
			$table->string('description', 255);
			$table->float("amount", 10);
			$table->float("taxes", 10);
			$table->float("total", 10);
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->timestamps();

		});


		Schema::dropIfExists('invoices_sent');
		Schema::create('invoices_sent', function(Blueprint $table)
		{
			//
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('name', 100);
			$table->float("amount", 10);
			$table->string('address', 100);
			$table->string('fiscalcode', 100);
			$table->timestamps();
		});

		Schema::dropIfExists('invoices_amendment');
		Schema::create('invoices_amendment', function(Blueprint $table)
		{
			//
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('invoices_sent_id')->unsigned()->nullable();
			$table->float("amount", 10)->default(0);
			$table->timestamps();

			$table->foreign('invoices_sent_id')->references('id')->on('invoices_sent')->onDelete('cascade');
		});

        DB::unprepared("ALTER TABLE invoices_sent AUTO_INCREMENT = 1000000;");
        DB::unprepared("ALTER TABLE invoices_amendment AUTO_INCREMENT = 1000000;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('invoices_amendment');
		Schema::dropIfExists('invoices_sent');
		Schema::dropIfExists('invoices_providers');
		Schema::dropIfExists('providers');
	}

}
