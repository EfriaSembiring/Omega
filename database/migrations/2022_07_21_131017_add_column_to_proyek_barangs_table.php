<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProyekBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyek_barangs', function (Blueprint $table) {
            $table->string('proyek_name');
            $table->string('barang_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyek_barangs', function (Blueprint $table) {
            $table->dropColumn('proyek_name');
            $table->dropColumn('barang_name');
        });
    }
}
