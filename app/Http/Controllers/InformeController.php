<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Balance;
use App\Credito;
use App\Cuotum;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Informe;
use App\Movimiento;
use App\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;

class InformeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $informe = Informe::where('fecha', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $informe = Informe::paginate($perPage);
        }

        return view('informe.index', compact('informe'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('informe.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $requestData = $request->all();

        Informe::create($requestData);

        return redirect('informe')->with('flash_message', 'Informe added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $informe = Informe::findOrFail($id);

        return view('informe.show', compact('informe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $informe = Informe::findOrFail($id);

        return view('informe.edit', compact('informe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $requestData = $request->all();

        $informe = Informe::findOrFail($id);
        $informe->update($requestData);

        return redirect('informe')->with('flash_message', 'Informe updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Informe::destroy($id);

        return redirect('informe')->with('flash_message', 'Informe deleted!');
    }

    public function abrirInforme()
    {
        $informe_id = Informe::where('estado', '=', 1)
            ->select('id')
            ->orderBy('id', 'desc')
            ->get()->first();
        if ($informe_id != "") {
            return json_encode(array('confirmacion' => 0));
        }
        Informe::create([
            'fecha' => Carbon::now(),
            'fecha_cierre' => Carbon::now(),
            'estado' => 1,
            'ingresos' => 0,
            'egresos' => 0,
            'saldo' => 0,
            'cargado' => 0,
            'prestado' => 0,
            'cobrado' => 0,
            'gastado' => 0,
            'porCobrar' => 0
        ]);
        $informe_id = Informe::where('estado', '=', 1)
            ->select('id')
            ->orderBy('id', 'desc')
            ->get()->first()->id;
        $trabajadores = Trabajador::where('habilitado', '=', 1)->get();
        foreach ($trabajadores as $item) {
            Balance::create([
                'fecha' => Carbon::now(),
                'fecha_cierre' => Carbon::now(),
                'estado' => 1,
                'trabajador_id' => $item->id,
                'informe_id' => $informe_id,
                'ingresos' => 0,
                'egresos' => 0,
                'saldo' => 0,
                'cargado' => 0,
                'prestado' => 0,
                'cobrado' => 0,
                'gastado' => 0,
                'porCobrar' => 0
            ]);
        }
        return json_encode(array('confirmacion' => 1));
    }

    public function cerrarInforme()
    {

        $informe_id = Informe::where('estado', '=', 1)
            ->select('id')
            ->orderBy('id', 'desc')
            ->get()->first();
        if ($informe_id != "") {
            $informe_id = $informe_id->id;
        } else {
            return json_encode(array('confirmacion' => 0));
        }

        $balance = Balance::where('informe_id', '=', $informe_id)->get();
        /*Informe::create([
            'fecha'=>Carbon::now(),
            'fecha_cierre'=>Carbon::now(),
            'estado'=>1
        ]);*/
        foreach ($balance as $item) {
            $balance_id = $item->id;
            $ingresos = Movimiento::where('balance_id', '=', $balance_id)
                ->where('tipo', '=', 1)
                ->select(DB::raw('sum(monto) as ingresos'))
                ->get()->first();
            if ($ingresos->ingresos != null) {
                $egresos = Movimiento::where('balance_id', '=', $balance_id)
                    ->where('tipo', '=', 2)
                    ->select(DB::raw('sum(monto) as egresos'))
                    ->get()->first();
                if ($egresos->egresos != null) {
                    $ingresos = $ingresos->ingresos;
                    $egresos = $egresos->egresos;
                    $saldo = $ingresos - $egresos;
                } else {
                    $ingresos = $ingresos->ingresos;
                    $egresos = 0;
                    $saldo = $ingresos;
                }
            } else {
                $egresos = 0;
                $ingresos = 0;
                $saldo = 0;
            }

            $prestado = Movimiento::where('balance_id', '=', $balance_id)
                ->where('detalle', '=', 'PRESTAMO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();

            if ($prestado->monto != null) {
                $prestado = $prestado->monto;
            } else {
                $prestado = 0;
            }
            $gastado = Movimiento::where('balance_id', '=', $balance_id)
                ->where('detalle', '=', 'GASTO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();

            if ($gastado->monto != null) {
                $gastado = $gastado->monto;
            } else {
                $gastado = 0;
            }
            $cobrado = Movimiento::where('balance_id', '=', $balance_id)
                ->where('detalle', '=', 'COBRO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();
            if ($cobrado->monto != null) {
                $cobrado = $cobrado->monto;
            } else {
                $cobrado = 0;
            }
            $cargado = Movimiento::where('balance_id', '=', $balance_id)
                ->where('detalle', '=', 'CARGA')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();
            if ($cargado->monto != null) {
                $cargado = $cargado->monto;
            } else {
                $cargado = 0;
            }


            $creditos = Credito::where('trabajador_id', '=', Balance::find($balance_id)->trabajador_id)->get();
            $porCobrar = 0;
            foreach ($creditos as $variable) {
                $diasFaltantes = $variable->dias - count(Cuotum::where('credito_id', '=', $variable->id)->get());
                $cuota = $variable->cuota;
                $porCobrar = $porCobrar + ($cuota * $diasFaltantes) - $variable->acuenta;
            }

            Balance::find($item->id)->update(['estado' => 0,
                'fecha_cierre' => Carbon::now(),
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'saldo' => $saldo,
                'cargado' => $cargado,
                'prestado' => $prestado,
                'cobrado' => $cobrado,
                'gastado' => $gastado,
                'porCobrar' => $porCobrar
            ]);
            /* Balance::create([
                 'fecha'=>Carbon::now(),
                 'estado'=>1,
                 'trabajador_id'=>$item->trabajador_id,
                 'informe_id'=>$informe_id
             ]);*/
        }
        $datos = json_decode($this->verUltimo());
        Informe::find($informe_id)->update([
            'estado' => 0,
            'fecha_cierre' => Carbon::now(),
            'ingresos' => $datos->ingresos,
            'egresos' => $datos->egresos,
            'saldo' => $datos->saldo,
            'cargado' => $datos->cargado,
            'prestado' => $datos->prestado,
            'cobrado' => $datos->cobrado,
            'gastado' => $datos->gastado,
            'porCobrar' => $datos->porCobrar
        ]);
        return json_encode(array('confirmacion' => 1));

        //falta hacer que guarde un el Acuenta del credito para tenerlo como hixtoria de ese informe
    }

    public function verUltimo()
    {
        $informe = Informe::select('id', 'fecha', 'fecha_cierre', 'estado')->orderBy('id', 'desc')->get()->first();
        if ($informe != "") {
            $informe_id = $informe->id;
            $informe_fecha = $informe->fecha;
            $informe_fecha_cierre = $informe->fecha_cierre;
            $informe_estado = $informe->estado;
        } else {
            return json_encode(array("confirmacion" => 0));
        }
        $balances = Balance::where('informe_id', '=', $informe_id)->get();

        $tsaldo = 0;
        $tingresos = 0;
        $tegresos = 0;
        $tcargado = 0;
        $tprestado = 0;
        $tcobrado = 0;
        $tgastado = 0;
        $tporCobrar = 0;
        foreach ($balances as $item) {
            if ($item->id != "") {
                $ingresos = Movimiento::where('balance_id', '=', $item->id)
                    ->where('tipo', '=', 1)
                    ->select(DB::raw('sum(monto) as ingresos'))
                    ->get()->first();

                if ($ingresos->ingresos != null) {

                    $egresos = Movimiento::where('balance_id', '=', $item->id)
                        ->where('tipo', '=', 2)
                        ->select(DB::raw('sum(monto) as egresos'))
                        ->get()->first();
                    if ($egresos->egresos != null) {
                        $ingresos = $ingresos->ingresos;
                        $egresos = $egresos->egresos;
                        $saldo = $ingresos - $egresos;
                    } else {

                        $ingresos = $ingresos->ingresos;
                        $egresos = 0;
                        $saldo = $ingresos;

                    }
                } else {
                    $egresos = 0;
                    $ingresos = 0;
                    $saldo = 0;
                }

            } else {
                return json_encode(array("confirmacion" => 0));
            }

            $prestado = Movimiento::where('balance_id', '=', $item->id)
                ->where('detalle', '=', 'PRESTAMO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();

            if ($prestado->monto != null) {
                $prestado = $prestado->monto;
            } else {
                $prestado = 0;
            }
            $gastado = Movimiento::where('balance_id', '=', $item->id)
                ->where('detalle', '=', 'GASTO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();

            if ($gastado->monto != null) {
                $gastado = $gastado->monto;
            } else {
                $gastado = 0;
            }
            $cobrado = Movimiento::where('balance_id', '=', $item->id)
                ->where('detalle', '=', 'COBRO')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();
            if ($cobrado->monto != null) {
                $cobrado = $cobrado->monto;
            } else {
                $cobrado = 0;
            }
            $cargado = Movimiento::where('balance_id', '=', $item->id)
                ->where('detalle', '=', 'CARGA')
                ->select(DB::raw('sum(monto) as monto'))->get()->first();
            if ($cargado->monto != null) {
                $cargado = $cargado->monto;
            } else {
                $cargado = 0;
            }
            $creditos = Credito::where('trabajador_id', '=', Balance::find($item->id)->trabajador_id)->get();
            $porCobrar = 0;
            foreach ($creditos as $item) {
                $diasFaltantes = $item->dias - count(Cuotum::where('credito_id', '=', $item->id)->get());
                $cuota = $item->cuota;
                $porCobrar = $porCobrar + ($cuota * $diasFaltantes) - $item->acuenta;
            }
            $tsaldo = $tsaldo + $saldo;
            $tingresos = $tingresos + $ingresos;
            $tegresos = $tegresos + $egresos;
            $tprestado = $tprestado + $prestado;
            $tcobrado = $tcobrado + $cobrado;
            $tgastado = $tgastado + $gastado;
            $tcargado = $tcargado + $cargado;
            $tporCobrar = $tporCobrar + $porCobrar;
        }
        return json_encode(array(
            "codigo" => $informe_id . "",
            "fecha" => $informe_fecha . "",
            "fecha_cierre" => $informe_fecha_cierre . "",
            "estado" => $informe_estado . "",
            "ingresos" => $tingresos . "",
            "egresos" => $tegresos . "",
            "saldo" => $tsaldo . "",
            "cargado" => $tcargado . "",
            "prestado" => $tprestado . "",
            "gastado" => $tgastado . "",
            "cobrado" => $tcobrado . "",
            "porCobrar" => $tporCobrar . ""
        ));
    }

    public function verInforme($informe_id)
    {

        $informe = Informe::where('id','=',$informe_id)->get()->first();
        if ($informe->estado == 1) {
            if ($informe != "") {
                $informe_id = $informe->id;
                $informe_fecha = $informe->fecha;
                $informe_fecha_cierre = $informe->fecha_cierre;
                $informe_estado = $informe->estado;
            } else {
                return json_encode(array("confirmacion" => 0));
            }
            $balances = Balance::where('informe_id', '=', $informe_id)->get();

            $tsaldo = 0;
            $tingresos = 0;
            $tegresos = 0;
            $tcargado = 0;
            $tprestado = 0;
            $tcobrado = 0;
            $tgastado = 0;
            $tporCobrar = 0;
            foreach ($balances as $item) {
                if ($item->id != "") {
                    $ingresos = Movimiento::where('balance_id', '=', $item->id)
                        ->where('tipo', '=', 1)
                        ->select(DB::raw('sum(monto) as ingresos'))
                        ->get()->first();

                    if ($ingresos->ingresos != null) {

                        $egresos = Movimiento::where('balance_id', '=', $item->id)
                            ->where('tipo', '=', 2)
                            ->select(DB::raw('sum(monto) as egresos'))
                            ->get()->first();
                        if ($egresos->egresos != null) {
                            $ingresos = $ingresos->ingresos;
                            $egresos = $egresos->egresos;
                            $saldo = $ingresos - $egresos;
                        } else {

                            $ingresos = $ingresos->ingresos;
                            $egresos = 0;
                            $saldo = $ingresos;

                        }
                    } else {
                        $egresos = 0;
                        $ingresos = 0;
                        $saldo = 0;
                    }

                } else {
                    return json_encode(array("confirmacion" => 0));
                }

                $prestado = Movimiento::where('balance_id', '=', $item->id)
                    ->where('detalle', '=', 'PRESTAMO')
                    ->select(DB::raw('sum(monto) as monto'))->get()->first();

                if ($prestado->monto != null) {
                    $prestado = $prestado->monto;
                } else {
                    $prestado = 0;
                }
                $gastado = Movimiento::where('balance_id', '=', $item->id)
                    ->where('detalle', '=', 'GASTO')
                    ->select(DB::raw('sum(monto) as monto'))->get()->first();

                if ($gastado->monto != null) {
                    $gastado = $gastado->monto;
                } else {
                    $gastado = 0;
                }
                $cobrado = Movimiento::where('balance_id', '=', $item->id)
                    ->where('detalle', '=', 'COBRO')
                    ->select(DB::raw('sum(monto) as monto'))->get()->first();
                if ($cobrado->monto != null) {
                    $cobrado = $cobrado->monto;
                } else {
                    $cobrado = 0;
                }
                $cargado = Movimiento::where('balance_id', '=', $item->id)
                    ->where('detalle', '=', 'CARGA')
                    ->select(DB::raw('sum(monto) as monto'))->get()->first();
                if ($cargado->monto != null) {
                    $cargado = $cargado->monto;
                } else {
                    $cargado = 0;
                }
                $creditos = Credito::where('trabajador_id', '=', Balance::find($item->id)->trabajador_id)->get();
                $porCobrar = 0;
                foreach ($creditos as $item) {
                    $diasFaltantes = $item->dias - count(Cuotum::where('credito_id', '=', $item->id)->get());
                    $cuota = $item->cuota;
                    $porCobrar = $porCobrar + ($cuota * $diasFaltantes) - $item->acuenta;
                }
                $tsaldo = $tsaldo + $saldo;
                $tingresos = $tingresos + $ingresos;
                $tegresos = $tegresos + $egresos;
                $tprestado = $tprestado + $prestado;
                $tcobrado = $tcobrado + $cobrado;
                $tgastado = $tgastado + $gastado;
                $tcargado = $tcargado + $cargado;
                $tporCobrar = $tporCobrar + $porCobrar;
            }
            return json_encode(array(
                "codigo" => $informe_id . "",
                "fecha" => $informe_fecha . "",
                "fecha_cierre" => $informe_fecha_cierre . "",
                "estado" => $informe_estado . "",
                "ingresos" => $tingresos . "",
                "egresos" => $tegresos . "",
                "saldo" => $tsaldo . "",
                "cargado" => $tcargado . "",
                "prestado" => $tprestado . "",
                "gastado" => $tgastado . "",
                "cobrado" => $tcobrado . "",
                "porCobrar" => $tporCobrar . ""
            ));
        }else{
            $datos = Informe::find($informe_id);
            return json_encode(array(
                "codigo" => $datos->id . "",
                "fecha" => $datos->fecha . "",
                "fecha_cierre" => $datos->fecha_cierre . "",
                "estado" => $datos->estado . "",
                "ingresos" => $datos->ingresos . "",
                "egresos" => $datos->egresos . "",
                "saldo" => $datos->saldo . "",
                "cargado" => $datos->cargado . "",
                "prestado" => $datos->prestado . "",
                "gastado" => $datos->gastado . "",
                "cobrado" => $datos->cobrado . "",
                "porCobrar" => $datos->porCobrar . ""
            ));
        }

    }

    function verHistorico()
    {
        $informes = Informe::select('id', 'fecha', 'fecha_cierre', 'estado')->orderBy('id', 'desc')->get();
        return json_encode(array("informes" => $informes));
    }

    public
    function mostrarIngresos($informe_id)
    {

        $ingresos = Movimiento::join('balances as b', 'b.id', '=', 'balance_id')
            ->join('trabajadors as t', 't.id', '=', 'b.trabajador_id')
            ->where('b.informe_id', '=', $informe_id)
            ->where('tipo', '=', 1)
            ->select('movimientos.id as id', 'movimientos.fecha', 'monto', 'detalle', 'descripcion', 'nombre')
            ->orderBy('movimientos.id', 'desc')->get();
        return json_encode(array("ingresos" => $ingresos));
    }

    public
    function mostrarEgresos($informe_id)
    {

        $egresos = Movimiento::join('balances as b', 'b.id', '=', 'balance_id')
            ->join('trabajadors as t', 't.id', '=', 'b.trabajador_id')
            ->where('b.informe_id', '=', $informe_id)
            ->where('tipo', '=', 2)
            ->select('movimientos.id as id', 'movimientos.fecha', 'monto', 'detalle', 'descripcion', 'nombre')
            ->orderBy('movimientos.id', 'desc')->get();

        return json_encode(array("egresos" => $egresos));
    }

    public
    function mostrarGastos($informe_id)
    {

        $gastos = Movimiento::join('balances as b', 'b.id', '=', 'balance_id')
            ->join('trabajadors as t', 't.id', '=', 'b.trabajador_id')
            ->where('b.informe_id', '=', $informe_id)
            ->where('detalle', '=', 'GASTO')
            ->select('movimientos.id as id', 'movimientos.fecha', 'monto', 'detalle', 'descripcion', 'nombre')
            ->orderBy('movimientos.id', 'desc')->get();

        return json_encode(array("gastos" => $gastos));
    }
}
