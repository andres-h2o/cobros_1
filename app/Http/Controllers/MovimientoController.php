<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Movimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
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
            $movimiento = Movimiento::where('fecha', 'LIKE', "%$keyword%")
                ->orWhere('monto', 'LIKE', "%$keyword%")
                ->orWhere('tipo', 'LIKE', "%$keyword%")
                ->orWhere('balance_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $movimiento = Movimiento::paginate($perPage);
        }

        return view('movimiento.index', compact('movimiento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('movimiento.create');
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

        Movimiento::create($requestData);

        return redirect('movimiento')->with('flash_message', 'Movimiento added!');
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
        $movimiento = Movimiento::findOrFail($id);

        return view('movimiento.show', compact('movimiento'));
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
        $movimiento = Movimiento::findOrFail($id);

        return view('movimiento.edit', compact('movimiento'));
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

        $movimiento = Movimiento::findOrFail($id);
        $movimiento->update($requestData);

        return redirect('movimiento')->with('flash_message', 'Movimiento updated!');
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
        Movimiento::destroy($id);

        return redirect('movimiento')->with('flash_message', 'Movimiento deleted!');
    }

    public function nuevoGasto($monto, $descripcion, $trabajador_id)
    {
        $balance_id = Balance::where('trabajador_id', '=', $trabajador_id)
            ->where('estado', '=', 1)
            ->select('id', 'fecha')->orderBy('id', 'desc')->get()->first();
        /*Preguntar por el saldo del trabajador solo podra registrar gasto si su saldo es suficiente*/
        if (!empty($balance_id)) {
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
                    $saldo = $ingresos->ingresos - $egresos->egresos;
                } else {
                    $saldo = $ingresos->ingresos;
                }
            } else {
                return json_encode(array("confirmacion" => 0));
            }

        } else {
            return json_encode(array("confirmacion" => 0));
        }
        if ($saldo >= $monto) {
            Movimiento::create([
                'fecha' => Carbon::now()->format('Y-m-d'),
                'monto' => $monto,
                'detalle' => 'GASTO',
                'descripcion' => $descripcion,
                'tipo' => 2,
                'balance_id' => $balance_id->id
            ]);
            return json_encode(array("confirmacion" => 1));
        } else {
            return json_encode(array("confirmacion" => 0));
        }

    }

    public function abonar($monto,$trabajador_id)
    {
        $balance_id = Balance::where('trabajador_id', '=', $trabajador_id)
            ->where('estado', '=', 1)
            ->select('id', 'fecha')->orderBy('id', 'desc')->get()->first();
        if($balance_id!=""){
            Movimiento::create([
                'fecha' => Carbon::now()->format('Y-m-d'),
                'monto' => $monto,
                'detalle' => 'CARGA',
                'descripcion' =>'',
                'tipo' => 1,
                'balance_id' => $balance_id->id
            ]);
            return json_encode(array("confirmacion" => 1));
        }

        return json_encode(array("confirmacion" => 1));
    }
}
