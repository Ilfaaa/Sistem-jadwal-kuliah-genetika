<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestWaktuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_waktu', function (Blueprint $table) {
            $table->id();
            $table->string('request');
            $table->string('manage');
            $table->integer('kode_waktu');
            $table->integer('kode_hari');
            $table->string('nama_hari');
            $table->string('kode_jam');
            $table->string('jam');
            $table->string('name');
            $table->string('image');
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
        Schema::dropIfExists('request_waktu');
    }
}
