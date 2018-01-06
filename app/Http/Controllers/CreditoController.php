<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Cliente;
use App\Cuotum;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Credito;
use App\Informe;
use App\Movimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditoController extends Controller
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
            $credito = Credito::where('monto', 'LIKE', "%$keyword%")
                ->orWhere('interes', 'LIKE', "%$keyword%")
                ->orWhere('fecha', 'LIKE', "%$keyword%")
                ->orWhere('dias', 'LIKE', "%$keyword%")
                ->orWhere('cuota', 'LIKE', "%$keyword%")
                ->orWhere('acuenta', 'LIKE', "%$keyword%")
                ->orWhere('estado', 'LIKE', "%$keyword%")
                ->orWhere('cliente_id', 'LIKE', "%$keyword%")
                ->orWhere('trabajador_id', 'LIKE', "%$keyword%")
                ->orWhere('informe_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $credito = Credito::paginate($perPage);
        }

        return view('credito.index', compact('credito'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('credito.create');
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

        Credito::create($requestData);

        return redirect('credito')->with('flash_message', 'Credito added!');
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
        $credito = Credito::findOrFail($id);

        return view('credito.show', compact('credito'));
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
        $credito = Credito::findOrFail($id);

        return view('credito.edit', compact('credito'));
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

        $credito = Credito::findOrFail($id);
        $credito->update($requestData);

        return redirect('credito')->with('flash_message', 'Credito updated!');
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
        Credito::destroy($id);

        return redirect('credito')->with('flash_message', 'Credito deleted!');
    }

    public function mostrarCuentas($clientes)
    {
        $cuentas = Credito::where('cliente_id', '=', $clientes)
            ->join('trabajadors as t', 't.id', '=', 'trabajador_id')
            ->select('creditos.id as id',
                'monto',
                'creditos.created_at as fecha',
                't.nombre as trabajador',
                't.id as trabajador_id',
                'estado'
            )->orderBy('fecha', 'desc')->orderBy('estado', 'desc')->get();
        return json_encode(array("cuentas" => $cuentas));
    }

    public function mostrarCuentasTrabajador($clientes,$trabajador)
    {
        $cuentas = Credito::where('cliente_id', '=', $clientes)
            ->where('trabajador_id', '=', $trabajador)
            ->join('trabajadors as t', 't.id', '=', 'trabajador_id')
            ->select('creditos.id as id',
                'monto',
                'creditos.created_at as fecha',
                't.nombre as trabajador',
                't.id as trabajador_id',
                'estado'
            )->orderBy('fecha', 'desc')->orderBy('estado', 'desc')->get();
        return json_encode(array("cuentas" => $cuentas));
    }

    public function verCuenta($credito)
    {
        $cuenta = Credito::where('id', '=', $credito)->get();
        if ($cuenta->first()->fecha == Carbon::now()->format('Y-m-d')) {
            $diasRetrasados = 0;
        } else {
            $retraso = Cuotum::where('credito_id', '=', $credito)
                ->select('fecha_pago as fecha')->orderBy('fecha_pago', 'desc')->get()->first();
            if ($retraso != "") {
                $retraso = $retraso->fecha;
                $dia = Carbon::createFromFormat('Y-m-d', $retraso)->isFuture();
                if (!$dia) {
                    $diasRetrasados = Carbon::createFromFormat('Y-m-d', $retraso)->diffInDays();
                    if ((Carbon::now()->isMonday() && $diasRetrasados >= 9) ||
                        (Carbon::now()->isTuesday() && $diasRetrasados >= 10) ||
                        (Carbon::now()->isWednesday() && $diasRetrasados >= 11) ||
                        (Carbon::now()->isThursday() && $diasRetrasados >= 12) ||
                        (Carbon::now()->isFriday() && $diasRetrasados >= 13) ||
                        (Carbon::now()->isSaturday() && $diasRetrasados >= 14) ||
                        (Carbon::now()->isSunday() && $diasRetrasados >= 15)) {
                        $diasRetrasados = $diasRetrasados - 2;
                    } elseif ((Carbon::now()->isMonday() && $diasRetrasados >= 1) ||
                        (Carbon::now()->isTuesday() && $diasRetrasados >= 2) ||
                        (Carbon::now()->isWednesday() && $diasRetrasados >= 3) ||
                        (Carbon::now()->isThursday() && $diasRetrasados >= 4) ||
                        (Carbon::now()->isFriday() && $diasRetrasados >= 5) ||
                        (Carbon::now()->isSaturday() && $diasRetrasados >= 6) ||
                        Carbon::now()->isSunday()) {
                        $diasRetrasados = $diasRetrasados - 1;
                    }
                } else {
                    $diasRetrasados = 0;
                }
            } else {
                $retraso = $cuenta->first()->fecha;
                $dia = Carbon::createFromFormat('Y-m-d', $retraso)->isFuture();
                if (!$dia) {
                    $diasRetrasados = Carbon::createFromFormat('Y-m-d', $retraso)->diffInDays();
                    if ((Carbon::now()->isMonday() && $diasRetrasados >= 9) ||
                        (Carbon::now()->isTuesday() && $diasRetrasados >= 10) ||
                        (Carbon::now()->isWednesday() && $diasRetrasados >= 11) ||
                        (Carbon::now()->isThursday() && $diasRetrasados >= 12) ||
                        (Carbon::now()->isFriday() && $diasRetrasados >= 13) ||
                        (Carbon::now()->isSaturday() && $diasRetrasados >= 14) ||
                        (Carbon::now()->isSunday() && $diasRetrasados >= 15)) {
                        $diasRetrasados = $diasRetrasados - 2;
                    } elseif ((Carbon::now()->isMonday() && $diasRetrasados > 1) ||
                        (Carbon::now()->isTuesday() && $diasRetrasados > 2) ||
                        (Carbon::now()->isWednesday() && $diasRetrasados > 3) ||
                        (Carbon::now()->isThursday() && $diasRetrasados > 4) ||
                        (Carbon::now()->isFriday() && $diasRetrasados > 5) ||
                        (Carbon::now()->isSaturday() && $diasRetrasados > 6) ||
                        Carbon::now()->isSunday()) {
                        $diasRetrasados = $diasRetrasados - 1;
                    }
                } else {
                    $diasRetrasados = 0;
                }
            }
        }
        $diasFaltantes = $cuenta->first()->dias - count(Cuotum::where('credito_id', '=', $credito)->get());
        $cuotas = Cuotum::where('credito_id', '=', $credito)->get();
        return json_encode(array("cuenta" => $cuenta, "retrazos" => $diasRetrasados, "faltantes" => $diasFaltantes, "cuotas" => $cuotas));//
    }

    public function nuevoCredito($monto, $interes, $fecha, $dias, $cuota, $cliente_id, $trabajador_id)
    {
        $balance_id = Balance::where('trabajador_id', '=', $trabajador_id)
            ->where('estado', '=', 1)
            ->select('id', 'fecha')->orderBy('id', 'desc')->get()->first();
        if (!empty($balance_id)) {
            $ingresos = Movimiento::where('balance_id', '=', $balance_id->id)
                ->where('tipo', '=', 1)
                ->select(DB::raw('sum(monto) as ingresos'))
                ->get()->first();
            if (!empty($ingresos)) {
                $egresos = Movimiento::where('balance_id', '=', $balance_id->id)
                    ->where('tipo', '=', 2)
                    ->select(DB::raw('sum(monto) as egresos'))
                    ->get()->first();
                if (!empty($egresos)) {
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
            $informe_id = Informe::select('id')
                ->orderBy('id', 'desc')
                ->get()->first()->id;
            Credito::create(
                [
                    'monto' => $monto,
                    'interes' => $interes,
                    'fecha' => $fecha,
                    'dias' => $dias,
                    'cuota' => $cuota,
                    'acuenta' => 0,
                    'cliente_id' => $cliente_id,
                    'trabajador_id' => $trabajador_id,
                    'estado' => 1,
                    'informe_id' => $informe_id
                ]
            );
            Cliente::find($cliente_id)->update(['conPrestamo' => 1]);
            $cliente = Cliente::find($cliente_id)->nombre;
            Movimiento::create([
                'fecha' => Carbon::now()->format('Y-m-d'),
                'monto' => $monto,
                'detalle' => 'PRESTAMO',
                'descripcion' => 'Prestamo a ' . $cliente,
                'tipo' => 2,
                'balance_id' => $balance_id->id
            ]);
            return json_encode(array("confirmacion" => 1));
        } else {
            return json_encode(array("confirmacion" => 0));
        }

    }
}
