<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surah_id');
            $table->foreign('surah_id')->on('surahs')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verses');
    }
}
