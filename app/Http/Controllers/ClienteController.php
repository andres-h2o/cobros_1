<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClienteController extends Controller
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
            $cliente = Cliente::where('nombre', 'LIKE', "%$keyword%")
                ->orWhere('celular', 'LIKE', "%$keyword%")
                ->orWhere('latitud', 'LIKE', "%$keyword%")
                ->orWhere('longitud', 'LIKE', "%$keyword%")
                ->orWhere('conPrestamo', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $cliente = Cliente::paginate($perPage);
        }

        return view('cliente.index', compact('cliente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */

    public function create()
    {
        return view('cliente.create');
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
            'latitud' => 'required',
            'longitud' => 'required'
        ]);
        $requestData = $request->all();

        Cliente::create($requestData);

        return redirect('cliente')->with('flash_message', 'Cliente added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('cliente.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('cliente.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);
        $requestData = $request->all();

        $cliente = Cliente::findOrFail($id);
        $cliente->update($requestData);

        return redirect('cliente')->with('flash_message', 'Cliente updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Cliente::destroy($id);

        return redirect('cliente')->with('flash_message', 'Cliente deleted!');
    }

    public function guardarCliente($nombre, $celular, $latitud, $longitud)
    {
        Cliente::create([
            'nombre' => $nombre,
            'celular' => $celular,
            'longitud' => $longitud,
            'latitud' => $latitud,
            'conPrestamo' => 0
        ]);
        return json_encode(array("confirmacion" => 1));
    }

    public function mostrar($trabajador)
    {
        $clientes = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->select('clientes.id as id',
                'nombre',
                'celular',
                'latitud',
                'longitud',
                'conPrestamo'
            )->where('c.trabajador_id', '=', $trabajador)
            ->where('c.estado', '=', 1)->orderBy('clientes.id', 'asc')->get();
        $clientesSin = Cliente::where('conPrestamo', '=', 0)
            ->select('clientes.id as id',
                'nombre',
                'celular',
                'latitud',
                'longitud',
                'conPrestamo'
            )->orderBy('clientes.id', 'asc')->get();
        return json_encode(array("clientesCon" => $clientes, "clientesSin" => $clientesSin));
    }

    public function mostrarPendientes($trabajador)
    {
        //clientes que si pagaron ese dia
        $clientes = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->where('c.estado', '=', 1)
            ->join('cuotas as cu', 'credito_id', '=', 'c.id')
            ->where('c.trabajador_id', '=', $trabajador)
            ->where('cu.estado', '=', 1)
            ->where('cu.fecha_pago', '=', Carbon::now()->format('Y-m-d'))
            ->select('clientes.id as id')->get();
        //return $clientes;
        //clientes que abonaron ese dia
        $clientesAbono = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->join('abonos as a', 'credito_id', '=', 'c.id')
            ->where('c.trabajador_id', '=', $trabajador)
            ->where('a.fecha', '=', Carbon::now()->format('Y-m-d'))
            ->select('clientes.id as id')->get();
        //clientes que se ubiese prestado ese mismo dia
        $clientesHoy = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->where('c.trabajador_id', '=', $trabajador)
            ->where('c.fecha', '=', Carbon::now()->format('Y-m-d'))
            ->select('clientes.id as id')->get();
        //clientes que registramos q no Pagaron por alguna razon
        $clientesNoPago = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->join('observacions as o', 'credito_id', '=', 'c.id')
            ->where('c.trabajador_id', '=', $trabajador)
            ->where('o.fecha', '=', Carbon::now()->format('Y-m-d'))
            ->select('clientes.id as id')->get();
        //clientesque faltan pagar
        $clientesTrabajador = Cliente::join('creditos as c', 'c.cliente_id', '=', 'clientes.id')
            ->whereNotIn('clientes.id', $clientes)
            ->whereNotIn('clientes.id', $clientesAbono)
            ->whereNotIn('clientes.id', $clientesHoy)
            ->whereNotIn('clientes.id', $clientesNoPago)
            ->select('clientes.id as id',
                'nombre',
                'celular',
                'latitud',
                'longitud',
                'conPrestamo'
            )->where('c.trabajador_id', '=', $trabajador)
            ->where('c.estado', '=', 1)->orderBy('clientes.id', 'asc')->get();
        //return $clientesTrabajador;
        return json_encode(array("clientes" => $clientesTrabajador));
    }

    public function actualizar($cliente_id, $nombre, $celular)
    {
        $cliente = Cliente::find($cliente_id);
        $cliente->update([
            'nombre'=>$nombre,
            'celular'=> $celular
        ]);
        return json_encode(array("confirmacion"=>1));
    }

    public function mostrarTodos()
    {
        return json_encode(array("clientes"=>Cliente::select('id','nombre','conPrestamo','celular')->orderBy('id','asc')->get()));
    }
    public function ubicarTodos()
    {
        $clientes =Cliente::join('creditos as c','c.cliente_id','=','clientes.id' )
            ->join('trabajadors as t', 't.id','=','trabajador_id')
            ->select('clientes.id as id',
                'clientes.nombre as nombre',
                'clientes.latitud as latitud',
                'clientes.longitud as longitud',
                't.nombre as trabajador')
            ->orderBy('t.nombre','asc')->get();
        return $clientes;
        return json_encode(array("clientes"=>$clientes));
    }
}
