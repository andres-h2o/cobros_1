<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('fecha')->nullable();
            $table->integer('monto')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('balance_id')->unsigned();
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade')->onUpdate('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimientos');
    }
}
