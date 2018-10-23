<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->dateTime('surf_datetime');
            $table->string('location', 255)->nullable();
            $table->string('location_lat', 255)->nullable();
            $table->string('location_lng', 255)->nullable();
            $table->longText('condition')->nullable();
            $table->integer('swell_height')->nullable();
            $table->string('swell_direction', 255)->nullable();
            $table->integer('wind_size')->nullable();
            $table->string('wind_direction', 255)->nullable();
            $table->string('wetsuits', 255)->nullable();
            $table->string('surfboard', 255)->nullable();
            $table->longText('comment')->nullable();
            $table->longText('memo')->nullable();
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
        Schema::dropIfExists('conditions');
    }
}
