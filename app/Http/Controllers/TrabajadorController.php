<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Informe;
use App\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class TrabajadorController extends Controller
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
            $trabajador = Trabajador::where('nombre', 'LIKE', "%$keyword%")
                ->orWhere('celular', 'LIKE', "%$keyword%")
                ->orWhere('latitud', 'LIKE', "%$keyword%")
                ->orWhere('longitud', 'LIKE', "%$keyword%")
                ->orWhere('password', 'LIKE', "%$keyword%")
                ->orWhere('habilitado', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $trabajador = Trabajador::paginate($perPage);
        }

        return view('trabajador.index', compact('trabajador'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('trabajador.create');
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
			'nombre' => 'required',
			'celular' => 'required'
		]);
        $requestData = $request->all();
        
        Trabajador::create($requestData);

        return redirect('trabajador')->with('flash_message', 'Trabajador added!');

    }
    public function guardarTrabajador($nombre,$celular,$password)
    {
        Trabajador::create([
            'nombre' =>$nombre,
            'celular' => $celular,
            'longitud'=>0,
            'latitud' => 0,
            'password' => $password,
            'habilitado'=>1
        ]);
        $trabajador_id=Trabajador::select('id')
            ->orderBy('id','desc')
            ->get()->first()->id;
        $informe_id=Informe::select('id')
            ->orderBy('id','desc')
            ->get()->first()->id;
        Balance::create([
            'fecha'=>Carbon::now(),
            'estado'=>1,
            'trabajador_id'=>$trabajador_id,
            'informe_id'=>$informe_id
        ]);
        return ;
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
        $trabajador = Trabajador::findOrFail($id);

        return view('trabajador.show', compact('trabajador'));
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
        $trabajador = Trabajador::findOrFail($id);

        return view('trabajador.edit', compact('trabajador'));
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
			'nombre' => 'required',
			'celular' => 'required'
		]);
        $requestData = $request->all();
        
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->update($requestData);

        return redirect('trabajador')->with('flash_message', 'Trabajador updated!');
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
        Trabajador::destroy($id);

        return redirect('trabajador')->with('flash_message', 'Trabajador deleted!');
    }
}
