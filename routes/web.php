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
Route::get('agregarCliente/{nombre}/{celular}/{carnet}/{direccion}/{latitud}/{longitud}/{trabajador}','ClienteController@guardarCliente');
//abrir informe general
Route::get('abrirInforme','InformeController@abrirInforme');
//cerrar informe general
Route::get('cerrarInforme','InformeController@cerrarInforme');
//login de SUPER Usuario
Route::get('iniciar/{nombre}/{password}','AdmController@login');
//login de trabajador
Route::get('iniciarTrabajador/{codigo}/{password}','TrabajadorController@login');
//devuelve el coigo para el nuevo trabajador
Route::get('nuevoTrabajador','TrabajadorController@codigo');
//muestra todos los trabajadores
Route::get('trabajadores/mostrar','TrabajadorController@mostrar');
//editar datos del trabajador
Route::get('trabajador/actualizar/{trabajador_id}/{nombre}/{celular}','TrabajadorController@actualizar');
//dar de baja
Route::get('trabajador/baja/{trabajador_id}','TrabajadorController@baja');
//actualizar Ubicacion
Route::get('trabajador/ubicacion/{latitud}/{longitud}/{trabajador_id}','TrabajadorController@ubicacion');
//actualizar Password trabajador
Route::get('trabajador/password/{passwordOld}/{passwordNe}/{trabajador_id}','TrabajadorController@actualizarPassword');
//actualizar Password Administrador
Route::get('su/password/{passwordOld}/{passwordNe}/{trabajador_id}','AdmController@actualizarPassword');
//get Estado
Route::get('trabajador/habilitado/{trabajador_id}','TrabajadorController@isHabilitado');

//muestra todos los clientes del trabajador incluyendo los clientes sin prestamo con nadie
Route::get('clientes/mostrar/todos/{trabajador}','ClienteController@mostrar');
//muestra todos los clientes
Route::get('clientes/mostrarTodos','ClienteController@mostrarTodos');
//muestra UBICACION DE todos los clientes POR SUS TRABAJADORES ACTUALES
Route::get('clientes/ubicarTodos','ClienteController@ubicarTodos');
//muestra los clientes que estan pendientes a pagar ese dia
Route::get('clientes/mostrar/pendientes/{trabajador}','ClienteController@mostrarPendientes');
//muestra  os creditos de un cliente
Route::get('clientes/mostrar/cuentas/{cliente}','CreditoController@mostrarCuentas');

//muestra  os creditos de un cliente realizadas por un trabajador
Route::get('clientes/mostrar/cuentas/trabajador/{cliente}/{trabajador}','CreditoController@mostrarCuentasTrabajador');

//editar datos del cliente
Route::get('clientes/actualizar/{cliente_id}/{nombre}/{celular}/{carnet}/{direccion}/{latitud}/{longitud}/{trabajador}','ClienteController@actualizar');
//ver detalles de un credito en especifico
Route::get('cuentas/ver/{credito}','CreditoController@verCuenta');

//registrar un nuevo credito para un cliente
Route::get('cuentas/nuevoCredito/{monto}/{interes}/{fecha}/{dias}/{cuota}/{cliente_id}/{trabajador_id}','CreditoController@nuevoCredito');

//registrar un nuevo abono para un credito
Route::get('abono/nuevoAbono/{monto}/{credito_id}/{trabajador_id}','AbonoController@nuevoAbono');
//eliminar un  abono para un credito
Route::get('abono/eliminarAbono/{idAbono}/{idTrabajador}','AbonoController@eliminarAbono');
//ver historico de abonos de un credito
Route::get('abono/ver/{credito_id}','AbonoController@verAbonos');

//registrar abono
Route::get('movimiento/abono/{monto}/{trabajador_id}','MovimientoController@abonar');

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

//ver ultimo informe
Route::get('informes/verUltimo','InformeController@verUltimo');
//ver un informe por su codigo
Route::get('informes/ver/{informe_id}','InformeController@verInforme');
//ver detalles de Movimientos como> Ingresos, Egresos y Gastos
Route::get('informes/verIngresos/{informe_id}','InformeController@mostrarIngresos');
Route::get('informes/verEgresos/{informe_id}','InformeController@mostrarEgresos');
Route::get('informes/verGastos/{informe_id}','InformeController@mostrarGastos');
//ver historico de informes>retorna todos los informes
Route::get('informes/verHistorico','InformeController@verHistorico');
Route::get('acc','ClienteController@actualizarId');

//notificar NO Pago
Route::get('observacion/nueva/{detalle}/{cliente_id}/{trabajador_id}','ObservacionController@nuevaObservacion');



