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
Route::get('clientes/actualizar/{cliente_id}/{nombre}/{celular}','ClienteController@actualizar');
Route::get('cuentas/ver/{credito}','CreditoController@verCuenta');

Route::get('cuentas/nuevoCredito/{monto}/{interes}/{fecha}/{dias}/{cuota}/{cliente_id}/{trabajador_id}','CreditoController@nuevoCredito');
Route::get('abono/nuevoAbono/{monto}/{credito_id}/{trabajador_id}','AbonoController@nuevoAbono');
Route::get('abono/ver/{credito_id}','AbonoController@verAbonos');


Route::get('movimiento/gasto/{monto}/{descripcion}/{trabajador_id}','MovimientoController@nuevoGasto');
Route::get('balance/verUltimo/{trabajador_id}','BalanceController@verUltimoBalance');
Route::get('balance/ver/{balance_id}','BalanceController@verBalance');
Route::get('balance/verIngresos/{balance_id}','BalanceController@mostrarIngresos');
Route::get('balance/verEgresos/{balance_id}','BalanceController@mostrarEgresos');
Route::get('balance/verGastos/{balance_id}','BalanceController@mostrarGastos');
Route::get('balance/verHistorico/{trabajador_id}','BalanceController@verHistorico');

