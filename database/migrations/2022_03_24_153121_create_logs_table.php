<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->string('origen')->nullable();
            $table->string('uri')->nullable();
            $table->integer('codigo')->nullable();
            $table->json('trama');
            $table->json('respuesta')->nullable();
            $table->json('cabecera')->nullable();
            $table->timestamp('creado_a');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
