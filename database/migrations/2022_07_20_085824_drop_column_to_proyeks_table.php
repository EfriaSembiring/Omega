<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnToProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyeks', function (Blueprint $table) {
            $table->dropColumn('barang_id');
            $table->dropColumn('jumlah')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyeks', function (Blueprint $table) {
            $table->integer('barang_id');
            $table->integer('jumlah')->default(0);
        });
    }
}
