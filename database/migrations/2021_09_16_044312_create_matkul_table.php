<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatkulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matkul', function (Blueprint $table) {
            $table->id('id_matkul');
            $table->string('kode_matkul', 40);
            $table->string('nama_matkul');
            $table->string('sks', 10);
            $table->char('kode_prodi', 10);
            $table->char('kode_semester', 10);
            $table->char('perkuliahan_semester', 10);
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
        Schema::dropIfExists('matkul');
    }
}
