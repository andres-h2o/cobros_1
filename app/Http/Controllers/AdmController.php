<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Adm;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;

class AdmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function login($nombre,$password)
    {

        $contra=Adm::where('nombre','=',$nombre)
            ->select('password')->first()->password;
       // return $contra;
        if($contra==$password){
            $arr=array('respuesta'=>1);
            return json_encode($arr);
        }
        $arr=array('respuesta'=>0);
        return json_encode($arr);
    }
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $adm = Adm::where('nombre', 'LIKE', "%$keyword%")
                ->orWhere('password', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $adm = Adm::paginate($perPage);
        }

        return view('adm.index', compact('adm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('adm.create');
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
        
        Adm::create($requestData);

        return redirect('adm')->with('flash_message', 'Adm added!');
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
        $adm = Adm::findOrFail($id);

        return view('adm.show', compact('adm'));
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
        $adm = Adm::findOrFail($id);

        return view('adm.edit', compact('adm'));
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
        
        $adm = Adm::findOrFail($id);
        $adm->update($requestData);

        return redirect('adm')->with('flash_message', 'Adm updated!');
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
        Adm::destroy($id);

        return redirect('adm')->with('flash_message', 'Adm deleted!');
    }
    public function actualizarPassword($passwordOld,$passwordNew, $nombre)
    {

        $trabajador=Trabajador::where('nombre','=',$nombre)->get()->first();
        if($trabajador->password==$passwordOld){
            Trabajador::find($$trabajador->trabajador_id)->update(['password'=>$passwordNew]);
            return json_encode(array("confirmacion"=>1));
        }
        return json_encode(array("confirmacion"=>0));
    }
}
