<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laptop_data', function (Blueprint $table) {
            $table->id();
            $table->string('merek');
            $table->string('tipe');
            $table->text('spesifikasi')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('stok')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_data');
    }
};
