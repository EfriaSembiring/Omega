<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBarangKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->foreignId('proyek_id');
            $table->date('tgl_brg_keluar')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropColumn('proyek_id');
            $table->dropColumn('tgl_brg_keluar');
        });
    }
}
