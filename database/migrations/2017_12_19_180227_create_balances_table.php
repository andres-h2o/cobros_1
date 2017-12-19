<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('fecha')->nullable();
            $table->integer('estado')->nullable();
            $table->integer('trabajador_id')->unsigned();
            $table->integer('informe_id')->unsigned();
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
        Schema::drop('balances');
    }
}
