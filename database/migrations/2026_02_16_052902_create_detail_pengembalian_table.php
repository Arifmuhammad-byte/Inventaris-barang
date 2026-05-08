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
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();

            // Foreign key ke pengembalians
            $table->foreignId('pengembalian_id')
                ->constrained('pengembalians')
                ->cascadeOnDelete();

            // Foreign key ke peminjaman, wajib diisi
            $table->foreignId('peminjaman_id')
                ->constrained('peminjaman')
                ->cascadeOnDelete();

            // Foreign key ke detail_peminjaman
            $table->foreignId('detail_peminjaman_id')
                ->constrained('detail_peminjaman')
                ->cascadeOnDelete();

            // Foreign key ke barang
            $table->foreignId('barang_id')
                ->constrained('barang')
                ->cascadeOnDelete();

            $table->integer('jumlah_kembali');
            $table->integer('jumlah_rusak')->default(0);
            $table->integer('jumlah_hilang')->default(0);

            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
