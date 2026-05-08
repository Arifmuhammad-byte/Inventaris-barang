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
        DB::statement("
            ALTER TABLE peminjaman 
            MODIFY status ENUM('Menunggu','Disetujui','Ditolak','Dikembalikan') 
            NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE peminjaman 
            MODIFY status ENUM('Menunggu','Disetujui','Dikembalikan') 
            NOT NULL
        ");
    }
};
