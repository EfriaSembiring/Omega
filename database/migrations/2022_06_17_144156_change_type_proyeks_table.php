<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyeks', function (Blueprint $table) {
            $table->foreignId('barang_id')->change();
            $table->foreignId('user_id')->change();
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
            $table->integer('barang_id')->change();
            $table->integer('user_id')->change();
        });
    }
}
