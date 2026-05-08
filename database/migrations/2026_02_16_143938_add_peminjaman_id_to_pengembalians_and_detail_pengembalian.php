<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom peminjaman_id di pengembalians
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->foreignId('peminjaman_id')
                  ->after('id')
                  ->constrained('peminjaman')
                  ->cascadeOnDelete();
        });

        // Tambah kolom peminjaman_id di detail_pengembalian
        Schema::table('detail_pengembalian', function (Blueprint $table) {
            $table->foreignId('peminjaman_id')
                  ->after('id')
                  ->constrained('peminjaman')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengembalian', function (Blueprint $table) {
            $table->dropForeign(['peminjaman_id']);
            $table->dropColumn('peminjaman_id');
        });

        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropForeign(['peminjaman_id']);
            $table->dropColumn('peminjaman_id');
        });
    }
};
