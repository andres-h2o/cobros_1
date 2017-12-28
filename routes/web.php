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
Route::resource('observacion', 'ObservacionController');
Route::resource('acuenta', 'AcuentaController');

//rutas webservices*******************************************************************
//************************************************************************************
//agregar un nuevo trfabajadpr
Route::get('agregarTrabajador/{nombre}/{celular}/{password}','TrabajadorController@guardarTrabajador');
//agregar nuevo cliente
Route::get('agregarCliente/{nombre}/{celular}/{latitud}/{longitud}','ClienteController@guardarCliente');
//cerrar informe general
Route::get('informe/cierre','InformeController@cerrarInforme');
//login de SUPER Usuario
Route::get('iniciar/{nombre}/{password}','AdmController@login');
//login de trabajador
Route::get('iniciarTrabajador/{codigo}/{password}','TrabajadorController@login');
//devuelve el coigo para el nuevo trabajador
Route::get('nuevoTrabajador','TrabajadorController@codigo');
//muestra todos los trabajadores
Route::get('trabajadores/mostrar','TrabajadorController@mostrar');

//muestra todos los clientes del trabajador incluyendo los clientes sin prestamo con nadie
Route::get('clientes/mostrar/todos/{trabajador}','ClienteController@mostrar');
//muestra los clientes que estan pendientes a pagar ese dia
Route::get('clientes/mostrar/pendientes/{trabajador}','ClienteController@mostrarPendientes');
//muestra  os creditos de un cliente
Route::get('clientes/mostrar/cuentas/{cliente}','CreditoController@mostrarCuentas');
//editar datos del cliente
Route::get('clientes/actualizar/{cliente_id}/{nombre}/{celular}','ClienteController@actualizar');
//ver detalles de un credito en especifico
Route::get('cuentas/ver/{credito}','CreditoController@verCuenta');

//registrar un nuevo credito para un cliente
Route::get('cuentas/nuevoCredito/{monto}/{interes}/{fecha}/{dias}/{cuota}/{cliente_id}/{trabajador_id}','CreditoController@nuevoCredito');

//registrar un nuevo abono para un credito
Route::get('abono/nuevoAbono/{monto}/{credito_id}/{trabajador_id}','AbonoController@nuevoAbono');
//ver historico de abonos de un credito
Route::get('abono/ver/{credito_id}','AbonoController@verAbonos');

//registrar nuevo gasto
Route::get('movimiento/gasto/{monto}/{descripcion}/{trabajador_id}','MovimientoController@nuevoGasto');
//ver ltimo balance de un trabajador
Route::get('balance/verUltimo/{trabajador_id}','BalanceController@verUltimoBalance');
//ver un balance por su codigo
Route::get('balance/ver/{balance_id}','BalanceController@verBalance');
//ver detalles de Movimientos como> Ingresos, Egresos y Gastos
Route::get('balance/verIngresos/{balance_id}','BalanceController@mostrarIngresos');
Route::get('balance/verEgresos/{balance_id}','BalanceController@mostrarEgresos');
Route::get('balance/verGastos/{balance_id}','BalanceController@mostrarGastos');
//ver historico de balances>retorna todos los balances de un trabajador
Route::get('balance/verHistorico/{trabajador_id}','BalanceController@verHistorico');

//notificar NO Pago
Route::get('observacion/nueva/{detalle}/{credito_id}/{trabajador_id}','ObservacionController@nuevaObservacion');



