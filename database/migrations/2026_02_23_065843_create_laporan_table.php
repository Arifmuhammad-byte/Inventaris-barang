<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id(); // Primary Key

            $table->enum('jenis_laporan', [
                'Inventaris',
                'Peminjaman',
                'Pengembalian'
            ]);

            $table->date('periode_awal');
            $table->date('periode_akhir');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamp('tanggal_cetak')
                  ->useCurrent();

            $table->string('file_laporan', 255)
                  ->nullable();

            $table->text('keterangan')
                  ->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};