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
        Schema::create('sold_laptops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptop_data')->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_id')->nullable();
            $table->decimal('sold_price', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamp('sold_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_laptops');
    }
};