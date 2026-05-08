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
            ALTER TABLE detail_pengembalian 
            MODIFY kondisi 
            ENUM('Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang') 
            NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE detail_pengembalian 
            MODIFY kondisi 
            ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') 
            NOT NULL
        ");
    }
};
