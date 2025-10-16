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
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('department');
        $table->string('phone');
        $table->date('start_date');
        $table->date('end_date');
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

};
