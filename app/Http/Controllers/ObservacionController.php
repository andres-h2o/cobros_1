<?php

namespace App\Http\Controllers;

use App\Credito;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Observacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ObservacionController extends Controller
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
            $observacion = Observacion::where('fecha', 'LIKE', "%$keyword%")
                ->orWhere('detalle', 'LIKE', "%$keyword%")
                ->orWhere('credito_id', 'LIKE', "%$keyword%")
                ->orWhere('trabajador_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $observacion = Observacion::paginate($perPage);
        }

        return view('observacion.index', compact('observacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('observacion.create');
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
        
        Observacion::create($requestData);

        return redirect('observacion')->with('flash_message', 'Observacion added!');
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
        $observacion = Observacion::findOrFail($id);

        return view('observacion.show', compact('observacion'));
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
        $observacion = Observacion::findOrFail($id);

        return view('observacion.edit', compact('observacion'));
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
        
        $observacion = Observacion::findOrFail($id);
        $observacion->update($requestData);

        return redirect('observacion')->with('flash_message', 'Observacion updated!');
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
        Observacion::destroy($id);

        return redirect('observacion')->with('flash_message', 'Observacion deleted!');
    }

    public function nuevaObservacion($detalle, $cliente_id,$trabajador_id)
    {
        $credito_id = Credito::where('cliente_id', '=', $cliente_id)
            ->join('trabajadors as t', 't.id', '=', 'trabajador_id')
            ->select('creditos.id as id',
                'monto',
                'fecha',
                't.nombre as trabajador',
                'estado'
            )->orderBy('fecha', 'desc')->orderBy('estado', 'desc')->get()->first()->id;
        Observacion::create([
            'fecha'=>Carbon::now()->format('Y-m-d'),
            'detalle'=>$detalle,
            'credito_id'=>$credito_id,
            'trabajador_id'=>$trabajador_id
        ]);
        return json_encode(array("confirmacion"=>1));
    }
}
