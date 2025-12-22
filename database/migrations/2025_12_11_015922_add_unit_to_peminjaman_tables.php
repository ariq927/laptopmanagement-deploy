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
            $table->string('unit')->nullable()->after('department');
        });

        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('department');
        });
    }

    public function down()
    {
        Schema::table('data_peminjam', function (Blueprint $table) {
            $table->dropColumn('unit');
        });

        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }

};
