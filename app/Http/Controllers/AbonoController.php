<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Cliente;
use App\Credito;
use App\Cuotum;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Abono;
use App\Informe;
use App\Movimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbonoController extends Controller
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
            $abono = Abono::where('fecha', 'LIKE', "%$keyword%")
                ->orWhere('monto', 'LIKE', "%$keyword%")
                ->orWhere('credito_id', 'LIKE', "%$keyword%")
                ->orWhere('informe_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $abono = Abono::paginate($perPage);
        }

        return view('abono.index', compact('abono'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('abono.create');
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
        
        Abono::create($requestData);

        return redirect('abono')->with('flash_message', 'Abono added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $abono = Abono::findOrFail($id);

        return view('abono.show', compact('abono'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $abono = Abono::findOrFail($id);

        return view('abono.edit', compact('abono'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $abono = Abono::findOrFail($id);
        $abono->update($requestData);

        return redirect('abono')->with('flash_message', 'Abono updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Abono::destroy($id);

        return redirect('abono')->with('flash_message', 'Abono deleted!');
    }

    public function nuevoAbono($monto, $credito_id,$trabajador_id)
    {

        $informe_id=Informe::select('id')
            ->orderBy('id','desc')
            ->get()->first()->id;
        Abono::create([
           'monto'=>$monto,
           'fecha'=>Carbon::now()->format('Y-m-d'),
           'credito_id'=>$credito_id,
            'informe_id'=>$informe_id
        ]);
        //adicionar movimiento al trabajador como un nuevo ingreso del abono que recibe del cliente
        $balance_id=Balance::where('trabajador_id', '=', $trabajador_id)
            ->where('estado', '=', 1)
            ->select('id',  'fecha')->orderBy('id', 'desc')->get()->first();
        $credito= Credito::where('id','=',$credito_id)->get()->first();
        $cliente =Cliente::find($credito->cliente_id)->nombre;

        Movimiento::create([
            'fecha'=>Carbon::now()->format('Y-m-d'),
            'monto'=>$monto,
            'detalle'=>'COBRO',
            'descripcion'=>'Cobro a '.$cliente,
            'tipo'=>1,
            'balance_id'=>$balance_id->id
        ]);

        $abono=$credito->acuenta+$monto;
        $fecha=Carbon::yesterday()->format('Y-m-d');
        while($abono>=$credito->cuota){
            $fecha=Carbon::createFromFormat('Y-m-d',$fecha)->addDay()->format('Y-m-d');

            Cuotum::create([
                'monto'=>$credito->cuota,
                'fecha_pago'=>$fecha,
                'estado'=>1,
                'credito_id'=>$credito_id,
                'trabajador_id'=>$trabajador_id,
                'informe_id'=>$informe_id
            ]);
            $abono=$abono-$credito->cuota;
        }

        $credito= Credito::find($credito_id);
        $credito->update([
            'acuenta'=>$abono
        ]);
        $cuenta = Credito::where('id','=',$credito_id)->get();
        $diasFaltantes = $cuenta->first()->dias - count(Cuotum::where('credito_id', '=', $credito_id)->get());

        if ($diasFaltantes<=0){
            Credito::find($credito_id)->update([
                'estado'=>0
            ]);
            Cliente::find($credito->cliente_id)->update([
                "conPrestamo"=>0
            ]);
            return json_encode(array("confirmacion"=>2));
        }else{
            return json_encode(array("confirmacion"=>1));
        }

    }

    public function verAbonos($credito_id)
    {
        $abonos=Abono::where('credito_id','=',$credito_id)
            ->select('id','fecha','monto')->orderBy('id','desc')->get();
        return json_encode(array("abonos"=>$abonos));
    }
}
