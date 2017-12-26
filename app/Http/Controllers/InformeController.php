<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Credito;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Informe;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function cerrarInforme()
    {
        $informe_id=Informe::select('id')
            ->orderBy('id','desc')
            ->get()->first()->id;
        $creditos= Credito::select('id','acuenta')
            ->where('estado','=',1)->get();
        foreach($creditos as $item){
            Abono::create([
                'monto'=>$item->acuenta
            ]);
        }
        $informe = Informe::find($informe_id);
        $informe->update([
            'fecha_cierre'=>Carbon::now()
        ]);

        Informe::create($requestData);

        return redirect('informe')->with('flash_message', 'Informe added!');
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
        $informe = Informe::findOrFail($id);

        return view('informe.show', compact('informe'));
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
        $informe = Informe::findOrFail($id);

        return view('informe.edit', compact('informe'));
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
        
        $informe = Informe::findOrFail($id);
        $informe->update($requestData);

        return redirect('informe')->with('flash_message', 'Informe updated!');
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
        Informe::destroy($id);

        return redirect('informe')->with('flash_message', 'Informe deleted!');
    }
}
