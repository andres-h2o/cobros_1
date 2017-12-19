@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Cuotum {{ $cuotum->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/cuota') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/cuota/' . $cuotum->id . '/edit') }}" title="Edit Cuotum"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('cuota' . '/' . $cuotum->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Cuotum" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $cuotum->id }}</td>
                                    </tr>
                                    <tr><th> Monto </th><td> {{ $cuotum->monto }} </td></tr><tr><th> Fecha Pago </th><td> {{ $cuotum->fecha_pago }} </td></tr><tr><th> Estado </th><td> {{ $cuotum->estado }} </td></tr><tr><th> Credito Id </th><td> {{ $cuotum->credito_id }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
