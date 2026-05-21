<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatkulDosenTable extends Migration
{
    /**
     * Tabel relasi many-to-many antara mata kuliah dan dosen pengampu.
     * Digunakan untuk menentukan dosen tetap pada mata kuliah tertentu.
     */
    public function up()
    {
        Schema::create('matkul_dosen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matkul', 40);
            $table->string('kode_dosen', 30);
            $table->string('tahun_ajaran', 40);
            $table->timestamps();

            $table->unique(['kode_matkul', 'kode_dosen', 'tahun_ajaran'], 'matkul_dosen_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('matkul_dosen');
    }
}
