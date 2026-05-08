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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();

            // Relasi ke peminjaman
            $table->foreignId('peminjaman_id')
                  ->constrained('peminjaman')
                  ->cascadeOnDelete();

            $table->date('tanggal_pengembalian');
            $table->enum('kondisi_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            $table->integer('jumlah_rusak')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
