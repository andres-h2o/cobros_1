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

    public function actualizar($trabajador_id, $nombre, $celular)
    {
         Trabajador::find($trabajador_id)->update([
            'nombre'=>$nombre,
            'celular'=> $celular
        ]);
        return json_encode(array("confirmacion"=>1));
    }
    public function ubicacion($latitud, $longitud, $trabajador_id)
    {
         Trabajador::find($trabajador_id)->update([
            'latitud'=>$latitud,
            'longitud'=> $longitud
        ]);
        return json_encode(array("confirmacion"=>1));
    }
    public function baja($trabajador_id)
    {
        $informe_id=Informe::where('estado','=',1)
            ->select('id')
            ->orderBy('id','desc')
            ->get()->first();
        if ($informe_id!=""){
            return json_encode(array('confirmacion'=>0));
        }
        if(Trabajador::find($trabajador_id)->habilitado==0){
            Trabajador::find($trabajador_id)->update([
                'habilitado'=>1
            ]);
        }else{
            Trabajador::find($trabajador_id)->update([
                'habilitado'=>0
            ]);
        }
        return json_encode(array("confirmacion"=>1));
    }

    public function guardarTrabajador($nombre,$celular,$password)
    {
        Trabajador::create([
            'nombre' =>$nombre,
            'celular' => $celular,
            'longitud'=>"17",
            'latitud' => "17",
            'password' => $password,
            'habilitado'=>1
        ]);
        $trabajador_id=Trabajador::select('id')
            ->orderBy('id','desc')
            ->get()->first()->id;
        $informe_id=Informe::where('estado','=',1)->select('id')
            ->orderBy('id','desc')
            ->get()->first();
        if($informe_id!=""){
            Balance::create([
                'fecha'=>Carbon::now(),
                'fecha_cierre'=>Carbon::now(),
                'estado'=>1,
                'trabajador_id'=>$trabajador_id,
                'informe_id'=>$informe_id->id,
                'ingresos'=>0,
                'egresos'=>0,
                'saldo'=>0,
                'cargado'=>0,
                'prestado'=>0,
                'cobrado'=>0,
                'gastado'=>0,
                'porCobrar'=>0
            ]);
        }

        return json_encode(array("confirmacion"=>1)) ;
    }
    public function login($codigo,$password)
    {

        $contra=Trabajador::where('id','=',$codigo)
            ->select('password','habilitado')->get()->first();
        if($contra!=""){
            if($contra->habilitado==1){
                if( $contra->password==$password){
                    return json_encode(array('confirmacion'=>1));
                }else{
                    return json_encode(array('confirmacion'=>2));
                }
            }else{
                return json_encode(array('confirmacion'=>2));
            }


        }else{
            return json_encode(array('confirmacion'=>0));
        }
        return json_encode(array('confirmacion'=>0));
    }
    public function codigo()
    {
        $codigo=Trabajador::select('id')->orderBy('id','desc')->get()->first();
        //return $codigo;
        if($codigo!=""){
            $codigo=$codigo->id+1;

            return json_encode(array('codigo'=>$codigo));
        }else{
            return json_encode(array('codigo'=>1));
        }
    }

    public function mostrar()
    {
        $trabajadores=Trabajador::all();
        return json_encode(array("trabajadores"=>$trabajadores));
    }

    public function isHabilitado($trabajador_id)
    {
        return json_encode(array("confirmacion"=>Trabajador::find($trabajador_id)->habilitado));
    }

    public function actualizarPassword($passwordOld,$passwordNew, $trabajador_id)
    {

        $trabajador=Trabajador::where('id','=',$trabajador_id)->get()->first();
        if($trabajador->password==$passwordOld){
            Trabajador::find($trabajador_id)->update(['password'=>$passwordNew]);
            return json_encode(array("confirmacion"=>1));
        }
        return json_encode(array("confirmacion"=>0));
    }
}
