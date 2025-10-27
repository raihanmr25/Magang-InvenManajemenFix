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
        Schema::create('barang_pemakaian', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('nibar')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('nama');
            $table->text('spesifikasi')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('pemakai')->nullable();
            $table->string('status')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('identitas')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_bast')->nullable();
            $table->date('tgl_bast')->nullable();
            $table->string('dokumen')->nullable();
            $table->string('no_dok')->nullable();
            $table->date('tgl_dok')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('no_simda')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_pemakaian');
    }
};
