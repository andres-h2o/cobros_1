<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Credito;
use Illuminate\Http\Request;

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
     * @param  int  $id
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
     * @param  int  $id
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
     * @param  int  $id
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
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Credito::destroy($id);

        return redirect('credito')->with('flash_message', 'Credito deleted!');
    }
}
