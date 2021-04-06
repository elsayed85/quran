<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rubs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('start_surah_id');
            $table->foreign('start_surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('start_verse_id');
            $table->foreign('start_verse_id')->on('verses')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('end_surah_id');
            $table->foreign('end_surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('end_verse_id');
            $table->foreign('end_verse_id')->on('verses')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rubs');
    }
}
