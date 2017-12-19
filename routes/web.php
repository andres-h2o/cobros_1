<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('trabajador', 'TrabajadorController');
Route::resource('informe', 'InformeController');
Route::resource('cliente', 'ClienteController');
Route::resource('credito', 'CreditoController');
Route::resource('cuota', 'CuotaController');
Route::resource('abono', 'AbonoController');
Route::resource('balance', 'BalanceController');
Route::resource('movimiento', 'MovimientoController');
Route::resource('adm', 'AdmController');

//rutas webservices
Route::get('trabajador/{nombre}/{celular}','TrabajadorController@guardarTrabajador');