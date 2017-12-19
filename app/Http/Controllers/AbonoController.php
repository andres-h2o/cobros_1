<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Abono;
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
}
