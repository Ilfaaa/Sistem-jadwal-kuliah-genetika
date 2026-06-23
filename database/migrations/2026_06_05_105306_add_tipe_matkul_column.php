<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeMatkulColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matkul', function (Blueprint $table) {
            $table->enum('tipe_matkul', ['wajib', 'pilihan'])->default('wajib')->after('jenis_matkul');
        });

        // Auto-detect: matkul yang namanya mengandung "pilihan" di-set sebagai pilihan
        DB::table('matkul')
            ->where('nama_matkul', 'LIKE', '%pilihan%')
            ->update(['tipe_matkul' => 'pilihan']);
    }

    public function down()
    {
        Schema::table('matkul', function (Blueprint $table) {
            $table->dropColumn('tipe_matkul');
        });
    }
}
