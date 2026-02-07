<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('request');
            $table->string('manage');
            $table->string('kode_manage', 40);
            $table->string('nama_manage');
            $table->string('sks', 10);
            $table->char('kode_prodi', 10);
            $table->string('kode_semester', 255);
            $table->string('nama_prodi');
            $table->string('nama_matkul');
            $table->string('nama_dosen');
            $table->integer('kapasitas_kelas')->unsigned();
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
        Schema::dropIfExists('request_kuliah');
    }
}
