<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_peminjam', function (Blueprint $table) {
            $table->string('status_peminjaman', 20)->default('active')->after('nomor_telepon');
            $table->index('status_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_peminjam', function (Blueprint $table) {
            $table->dropIndex(['status_peminjaman']);
            $table->dropColumn('status_peminjaman');
        });
    }
};