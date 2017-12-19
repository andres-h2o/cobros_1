<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('monto')->nullable();
            $table->integer('interes')->nullable();
            $table->date('fecha')->nullable();
            $table->integer('dias')->nullable();
            $table->integer('cuota')->nullable();
            $table->integer('acuenta')->nullable();
            $table->boolean('estado')->nullable();
            $table->integer('cliente_id')->unsigned();;
            $table->integer('trabajador_id')->unsigned();;
            $table->integer('informe_id')->unsigned();;
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('trabajador_id')->references('id')->on('trabajadors')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('creditos');
    }
}
