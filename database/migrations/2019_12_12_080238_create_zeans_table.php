<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZeansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zeans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid');
  
            $table->string('team1');
            $table->string('team2');
            $table->tinyInteger('over')->default(0);
            $table->longtext('content')->nullable();
            $table->string('bet')->nullable(); 
            $table->string('prevision')->nullable(); 
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('orders')->default(1);
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
        Schema::dropIfExists('zeans');
    }
}
