<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyzes', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('cid')->default(0);
            $table->smallInteger('uid')->default(0);
            $table->string('title');
            $table->text('description');
            $table->longtext('content')->nullable();
            $table->string('image')->nullable();
            $table->string('tag')->nullable();
            $table->string('slug');
            $table->smallInteger('status')->default(1);
            $table->smallInteger('hot')->default(0);
            $table->smallInteger('switch1')->default(0);
            $table->smallInteger('switch2')->default(0);             
            $table->integer('visit');
            $table->string('clip')->nullable();       
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
        Schema::dropIfExists('analyzes');
    }
}
