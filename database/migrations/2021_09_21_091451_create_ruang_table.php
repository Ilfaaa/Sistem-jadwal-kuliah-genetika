<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangTable extends Migration
{
    public function up()
    {
        Schema::create('ruang', function (Blueprint $table) {
            $table->id('kode_ruang');
            $table->string('nama_ruang');
            $table->integer('kapasitas')->default(0);
            $table->string('nama_prodi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang');
    }
}