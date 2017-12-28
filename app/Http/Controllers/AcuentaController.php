<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Acuentum;
use Illuminate\Http\Request;

class AcuentaController extends Controller
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
            $acuenta = Acuentum::where('fecha', 'LIKE', "%$keyword%")
                ->orWhere('monto', 'LIKE', "%$keyword%")
                ->orWhere('credito_id', 'LIKE', "%$keyword%")
                ->orWhere('informe_id', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $acuenta = Acuentum::paginate($perPage);
        }

        return view('acuenta.index', compact('acuenta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('acuenta.create');
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
        
        Acuentum::create($requestData);

        return redirect('acuenta')->with('flash_message', 'Acuentum added!');
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
        $acuentum = Acuentum::findOrFail($id);

        return view('acuenta.show', compact('acuentum'));
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
        $acuentum = Acuentum::findOrFail($id);

        return view('acuenta.edit', compact('acuentum'));
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
        
        $acuentum = Acuentum::findOrFail($id);
        $acuentum->update($requestData);

        return redirect('acuenta')->with('flash_message', 'Acuentum updated!');
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
        Acuentum::destroy($id);

        return redirect('acuenta')->with('flash_message', 'Acuentum deleted!');
    }
}
