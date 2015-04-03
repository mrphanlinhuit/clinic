<?php

use Illuminate\Database\Migrations\Migration as Migration;
use Illuminate\Database\Schema\Blueprint as Blueprint;

class CreateTagsTables extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('diagnostic_id')->unsigned()->nullable();
            $table->string("name", 32);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diagnostic_id')->references('id')->on('diagnostic')->onDelete('cascade');
        });
    }
        
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }

}