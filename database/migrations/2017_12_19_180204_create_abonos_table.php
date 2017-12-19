<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('fecha')->nullable();
            $table->integer('monto')->nullable();
            $table->integer('credito_id')->unsigned();
            $table->integer('informe_id')->unsigned();
            $table->foreign('credito_id')->references('id')->on('creditos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('informe_id')->references('id')->on('informes')->onDelete('cascade')->onUpdate('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('abonos');
    }
}
