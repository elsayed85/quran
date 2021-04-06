<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juzs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surah_id');
            $table->foreign('surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('start_verse')->nullable();
            $table->integer('end_verse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juzs');
    }
}
