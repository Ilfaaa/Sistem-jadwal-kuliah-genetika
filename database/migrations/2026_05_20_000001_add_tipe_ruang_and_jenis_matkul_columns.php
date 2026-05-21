<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTipeRuangAndJenisMatkulColumns extends Migration
{
    public function up()
    {
        // Tambah kolom tipe_ruang pada tabel ruang
        Schema::table('ruang', function (Blueprint $table) {
            $table->enum('tipe_ruang', ['reguler', 'laboratorium'])->default('reguler')->after('kapasitas');
        });

        // Tambah kolom jenis_matkul pada tabel matkul
        Schema::table('matkul', function (Blueprint $table) {
            $table->enum('jenis_matkul', ['teori', 'praktikum'])->default('teori')->after('sks');
        });

        // Auto-detect: ruang yang namanya mengandung "lab" di-set sebagai laboratorium
        DB::table('ruang')
            ->where('nama_ruang', 'LIKE', '%lab%')
            ->update(['tipe_ruang' => 'laboratorium']);

        // Auto-detect: matkul yang namanya mengandung "praktikum" di-set sebagai praktikum
        DB::table('matkul')
            ->where('nama_matkul', 'LIKE', '%praktikum%')
            ->update(['jenis_matkul' => 'praktikum']);
    }

    public function down()
    {
        Schema::table('ruang', function (Blueprint $table) {
            $table->dropColumn('tipe_ruang');
        });

        Schema::table('matkul', function (Blueprint $table) {
            $table->dropColumn('jenis_matkul');
        });
    }
}
