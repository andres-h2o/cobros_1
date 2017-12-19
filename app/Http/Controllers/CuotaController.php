<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cuotum;
use Illuminate\Http\Request;

class CuotaController extends Controller
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
            $cuota = Cuotum::where('monto', 'LIKE', "%$keyword%")
                ->orWhere('fecha_pago', 'LIKE', "%$keyword%")
                ->orWhere('estado', 'LIKE', "%$keyword%")
                ->orWhere('credito_id', 'LIKE', "%$keyword%")
                ->orWhere('trabajador_id', 'LIKE', "%$keyword%")
                ->orWhere('informe_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $cuota = Cuotum::paginate($perPage);
        }

        return view('cuota.index', compact('cuota'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cuota.create');
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
        $this->validate($request, [
			'monto' => 'required'
		]);
        $requestData = $request->all();
        
        Cuotum::create($requestData);

        return redirect('cuota')->with('flash_message', 'Cuotum added!');
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
        $cuotum = Cuotum::findOrFail($id);

        return view('cuota.show', compact('cuotum'));
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
        $cuotum = Cuotum::findOrFail($id);

        return view('cuota.edit', compact('cuotum'));
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
        $this->validate($request, [
			'monto' => 'required'
		]);
        $requestData = $request->all();
        
        $cuotum = Cuotum::findOrFail($id);
        $cuotum->update($requestData);

        return redirect('cuota')->with('flash_message', 'Cuotum updated!');
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
        Cuotum::destroy($id);

        return redirect('cuota')->with('flash_message', 'Cuotum deleted!');
    }
}
