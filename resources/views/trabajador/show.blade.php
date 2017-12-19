@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Trabajador {{ $trabajador->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/trabajador') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/trabajador/' . $trabajador->id . '/edit') }}" title="Edit Trabajador"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('trabajador' . '/' . $trabajador->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Trabajador" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $trabajador->id }}</td>
                                    </tr>
                                    <tr><th> Nombre </th><td> {{ $trabajador->nombre }} </td></tr><tr><th> Celular </th><td> {{ $trabajador->celular }} </td></tr><tr><th> Latitud </th><td> {{ $trabajador->latitud }} </td></tr><tr><th> Longitud </th><td> {{ $trabajador->longitud }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
