<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddJenisTipeMatkulToJadwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('jenis_matkul', 20)->nullable()->default(null)->after('matkul');
            $table->string('tipe_matkul', 20)->nullable()->default(null)->after('jenis_matkul');
        });

        // Backfill dari tabel matkul berdasarkan nama_matkul yang cocok
        DB::statement("
            UPDATE jadwal j
            INNER JOIN matkul m ON j.matkul = m.nama_matkul
            SET j.jenis_matkul = m.jenis_matkul,
                j.tipe_matkul  = m.tipe_matkul
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn(['jenis_matkul', 'tipe_matkul']);
        });
    }
}
