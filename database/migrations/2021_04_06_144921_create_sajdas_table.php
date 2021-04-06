<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSajdasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sajdas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surah_id');
            $table->foreign('surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('verse_id');
            $table->foreign('verse_id')->on('verses')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('obligatory')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sajdas');
    }
}
