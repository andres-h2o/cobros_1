<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Balance;
use App\Movimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
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
            $balance = Balance::where('fecha', 'LIKE', "%$keyword%")
                ->orWhere('estado', 'LIKE', "%$keyword%")
                ->orWhere('informe_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $balance = Balance::paginate($perPage);
        }

        return view('balance.index', compact('balance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('balance.create');
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

        Balance::create($requestData);

        return redirect('balance')->with('flash_message', 'Balance added!');
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
        $balance = Balance::findOrFail($id);

        return view('balance.show', compact('balance'));
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
        $balance = Balance::findOrFail($id);

        return view('balance.edit', compact('balance'));
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

        $balance = Balance::findOrFail($id);
        $balance->update($requestData);

        return redirect('balance')->with('flash_message', 'Balance updated!');
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
        Balance::destroy($id);

        return redirect('balance')->with('flash_message', 'Balance deleted!');
    }

    public function verUltimoBalance($trabajador_id)
    {
        $balance_id = Balance::where('trabajador_id', '=', $trabajador_id)
            ->where('estado', '=', 1)
            ->select('id', 'fecha','fecha_cierre','estado')->orderBy('id', 'desc')->get()->first();
        if ($balance_id!="") {
            $ingresos = Movimiento::where('balance_id', '=', $balance_id->id)
                ->where('tipo', '=', 1)
                ->select(DB::raw('sum(monto) as ingresos'))
                ->get()->first();

            if ($ingresos->ingresos != null) {

                $egresos = Movimiento::where('balance_id', '=', $balance_id->id)
                    ->where('tipo', '=', 2)
                    ->select(DB::raw('sum(monto) as egresos'))
                    ->get()->first();
                if ($egresos->egresos != null) {
                    $ingresos = $ingresos->ingresos;
                    $egresos =$egresos->egresos;
                    $saldo = $ingresos- $egresos;
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

        $prestado = Movimiento::where('balance_id', '=', $balance_id->id)
            ->where('detalle', '=', 'PRESTAMO')
            ->select(DB::raw('sum(monto) as monto'))->get()->first();

        if ($prestado->monto != null) {
            $prestado = $prestado->monto;
        } else {
            $prestado = 0;
        }
        $gastado = Movimiento::where('balance_id', '=', $balance_id->id)
            ->where('detalle', '=', 'GASTO')
            ->select(DB::raw('sum(monto) as monto'))->get()->first();

        if ($gastado->monto != null) {
            $gastado = $gastado->monto;
        } else {
            $gastado = 0;
        }
        $cobrado = Movimiento::where('balance_id', '=', $balance_id->id)
            ->where('detalle', '=', 'COBRO')
            ->select(DB::raw('sum(monto) as monto'))->get()->first();
        if ($cobrado->monto != null) {
            $cobrado = $cobrado->monto;
        } else {
            $cobrado = 0;
        }
        $cargado = Movimiento::where('balance_id', '=', $balance_id->id)
            ->where('detalle', '=', 'CARGA')
            ->select(DB::raw('sum(monto) as monto'))->get()->first();
        if ($cargado->monto != null) {
            $cargado = $cargado->monto;
        } else {
            $cargado = 0;
        }
        return json_encode(array(
            "codigo" => $balance_id->id . "",
            "fecha" => $balance_id->fecha . "",
            "fecha_cierre" => $balance_id->fecha_cierre . "",
            "estado" => $balance_id->estado . "",
            "ingresos" => $ingresos."",
            "egresos" => $egresos."",
            "saldo" => $saldo . "",
            "cargado" => $cargado."",
            "prestado" => $prestado."",
            "gastado" => $gastado."",
            "cobrado" => $cobrado."",
        ));
    }

    public
    function verBalance($balance_id)
    {

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
                $egresos =$egresos->egresos;
                $saldo = $ingresos- $egresos;
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
        return json_encode(array(
            "codigo" => $balance_id."",
            "fecha" => Balance::find($balance_id)->fecha . "",
            "fecha_cierre" => Balance::find($balance_id)->fecha_cierre . "",
            "estado" => Balance::find($balance_id)->estado . "",
            "ingresos" => $ingresos."",
            "egresos" => $egresos."",
            "saldo" => $saldo . "",
            "cargado" => $cargado."",
            "prestado" => $prestado."",
            "gastado" => $gastado."",
            "cobrado" => $cobrado."",
        ));
    }

    public
    function verHistorico($trabajador_id)
    {
        $informes = Balance::where('trabajador_id', '=', $trabajador_id)
            ->select('id','fecha','fecha_cierre','estado')->orderBy('id','desc')->get();
        return json_encode(array("informes" => $informes));
    }

    public
    function mostrarIngresos($balance_id)
    {

        $ingresos = Movimiento::where('balance_id', '=', $balance_id)
            ->join('trabajadors as t','t.id','=','trabajador_id')
            ->where('tipo', '=', 1)
            ->select('id','fecha','monto','detalle','descripcion','nombre')
            ->orderBy('id','desc')->get();
        return json_encode(array("ingresos" => $ingresos));
    }

    public
    function mostrarEgresos($balance_id)
    {

        $ingresos = Movimiento::where('balance_id', '=', $balance_id)
            ->join('trabajadors as t','t.id','=','trabajador_id')
            ->where('tipo', '=', 2)
            ->select('id','fecha','monto','detalle','descripcion','nombre')
            ->orderBy('id','desc')->get();

        return json_encode(array("egresos" => $ingresos));
    }

    public
    function mostrarGastos($balance_id)
    {

        $ingresos = Movimiento::where('balance_id', '=', $balance_id)
            ->join('trabajadors as t','t.id','=','trabajador_id')
            ->where('detalle', '=', 'GASTO')
            ->select('id','fecha','monto','detalle','descripcion','nombre')
            ->orderBy('id','desc')->get();

        return json_encode(array("gastos" => $ingresos));
    }
}
