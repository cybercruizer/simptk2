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
        Schema::create('satpends', function (Blueprint $table) {
            $table->string('nama_satpend');
            $table->integer('npsn')->unique();
            $table->enum('bentuk', ['SMA', 'SMK']);
            $table->string('alamat');
            $table->string('kecamatan_id');
            $table->string('kabupaten_id');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['N', 'S']);
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satpends');
    }
};