<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('data_peminjam', function (Blueprint $table) {
        $table->renameColumn('nomor_telepon', 'kode_pegawai');
    });
}

public function down()
{
    Schema::table('data_peminjam', function (Blueprint $table) {
        $table->renameColumn('kode_pegawai', 'nomor_telepon');
    });
}

};
