<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuliah', function (Blueprint $table) {
            $table->id('id_kuliah');
            $table->string('kode_kuliah', 40);
            $table->string('kode_matkul', 40);
            $table->string('kode_dosen', 30);
            $table->string('kode_kelas', 40);
            $table->char('kode_prodi',10);
            $table->char('kode_semester',10);
            $table->string('tahun_ajaran');
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
        Schema::dropIfExists('kuliah');
    }
}
