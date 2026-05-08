<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_unit', function (Blueprint $table) {

            $table->id();

            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->cascadeOnDelete();

            $table->string('kode_barang')->unique();

            $table->enum('kondisi', [
                'Baik',
                'Rusak Ringan',
                'Rusak Berat'
            ])->default('Baik');

            $table->enum('status', [
                'Tersedia',
                'Dipinjam'
            ])->default('Tersedia');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_unit');
    }
};
