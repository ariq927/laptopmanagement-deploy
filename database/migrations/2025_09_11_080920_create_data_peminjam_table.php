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
        Schema::create('data_peminjam', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // yang login
            $table->unsignedBigInteger('laptop_id'); // laptop yang dipinjam
            $table->string('nama');
            $table->string('departemen');
            $table->string('no_telp');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }

};
