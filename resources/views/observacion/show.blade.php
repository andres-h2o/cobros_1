@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Observacion {{ $observacion->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/observacion') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/observacion/' . $observacion->id . '/edit') }}" title="Edit Observacion"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('observacion' . '/' . $observacion->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Observacion" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $observacion->id }}</td>
                                    </tr>
                                    <tr><th> Fecha </th><td> {{ $observacion->fecha }} </td></tr><tr><th> Detalle </th><td> {{ $observacion->detalle }} </td></tr><tr><th> Credito Id </th><td> {{ $observacion->credito_id }} </td></tr><tr><th> Trabajador Id </th><td> {{ $observacion->trabajador_id }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
