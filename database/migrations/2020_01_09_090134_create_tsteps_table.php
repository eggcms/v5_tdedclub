<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTstepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tsteps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid');
            $table->string('team1');
            $table->string('team2');
            $table->string('team3');
            $table->integer('team1w')->default(0);
            $table->integer('team2w')->default(0);
            $table->integer('team3w')->default(0);
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
        Schema::dropIfExists('tsteps');
    }
}
