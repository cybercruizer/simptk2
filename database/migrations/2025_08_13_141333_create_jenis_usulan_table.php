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
        Schema::create('jenis_usulans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usulan');
            $table->integer('jumlah_dokumen');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
        Schema::create('usulans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('jenis_usulan_id')->references('id')->on('jenis_usulans')->onDelete('set null');
            $table->text('keterangan')->nullable();

            $table->enum('status_1', ['P', 'A', 'R'])->default('P');
            $table->timestamp('approved_at_1')->nullable();
            $table->timestamp('rejected_at_1')->nullable();
            $table->unsignedBigInteger('approved_by_1')->nullable();
            $table->unsignedBigInteger('rejected_by_1')->nullable();
            $table->text('reason_rejected_1')->nullable();

            $table->enum('status_2', ['P', 'A', 'R'])->default('P');           
            $table->timestamp('approved_at_2')->nullable();
            $table->timestamp('rejected_at_2')->nullable();           
            $table->unsignedBigInteger('approved_by_2')->nullable();
            $table->unsignedBigInteger('rejected_by_2')->nullable();
            $table->text('reason_rejected_2')->nullable();

            $table->foreign('approved_by_1')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by_1')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_2')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by_2')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulans');
        Schema::dropIfExists('jenis_usulans');
    }
};
