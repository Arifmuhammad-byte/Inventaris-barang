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
        Schema::table('detail_peminjaman', function (Blueprint $table) {

    $table->unsignedBigInteger('barang_unit_id')
          ->nullable()
          ->after('barang_id');

    $table->foreign('barang_unit_id')
          ->references('id')
          ->on('barang_unit')
          ->cascadeOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            //
        });
    }
};
