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
Route::get('agregarTrabajador/{nombre}/{celular}/{password}','TrabajadorController@guardarTrabajador');
Route::get('agregarCliente/{nombre}/{celular}/{latitud}/{longitud}','ClienteController@guardarCliente');
Route::get('informe/cierre','InformeController@cerrarInforme');
Route::get('iniciar/{nombre}/{password}','AdmController@login');
Route::get('iniciarTrabajador/{codigo}/{password}','TrabajadorController@login');
Route::get('nuevoTrabajador','TrabajadorController@codigo');
Route::get('trabajadores/mostrar','TrabajadorController@mostrar');
Route::get('clientes/mostrar/todos/{trabajador}','ClienteController@mostrar');
Route::get('clientes/mostrar/pendientes/{trabajador}','ClienteController@mostrarPendientes');
Route::get('clientes/mostrar/cuentas/{cliente}','CreditoController@mostrarCuentas');
Route::get('cuentas/ver/{credito}','CreditoController@verCuenta');