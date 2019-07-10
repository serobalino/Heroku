<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerteneceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertenece', function (Blueprint $table) {
            $table->unsignedBigInteger('id_du');
            $table->unsignedBigInteger('id_app');

            $table->foreign('id_du')->references('id_du')->on('duenos');
            $table->foreign('id_app')->references('id_app')->on('apps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pertenece', function (Blueprint $table) {
            $table->dropForeign(['id_du','id_app']);
        });
    }
}
