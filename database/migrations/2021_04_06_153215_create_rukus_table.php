<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rukus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manzil_id');
            $table->foreign('manzil_id')->on('manzils')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('surah_id');
            $table->foreign('surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('start_verse_id');
            $table->foreign('start_verse_id')->on('verses')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('rukus');
    }
}
