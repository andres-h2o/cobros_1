<div class="form-group {{ $errors->has('monto') ? 'has-error' : ''}}">
    <label for="monto" class="col-md-4 control-label">{{ 'Monto' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="monto" type="number" id="monto" value="{{ $cuotum->monto or ''}}" required>
        {!! $errors->first('monto', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('fecha_pago') ? 'has-error' : ''}}">
    <label for="fecha_pago" class="col-md-4 control-label">{{ 'Fecha Pago' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha_pago" type="date" id="fecha_pago" value="{{ $cuotum->fecha_pago or ''}}" >
        {!! $errors->first('fecha_pago', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('estado') ? 'has-error' : ''}}">
    <label for="estado" class="col-md-4 control-label">{{ 'Estado' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="estado" type="number" id="estado" value="{{ $cuotum->estado or ''}}" >
        {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('credito_id') ? 'has-error' : ''}}">
    <label for="credito_id" class="col-md-4 control-label">{{ 'Credito Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="credito_id" type="number" id="credito_id" value="{{ $cuotum->credito_id or ''}}" >
        {!! $errors->first('credito_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('trabajador_id') ? 'has-error' : ''}}">
    <label for="trabajador_id" class="col-md-4 control-label">{{ 'Trabajador Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="trabajador_id" type="number" id="trabajador_id" value="{{ $cuotum->trabajador_id or ''}}" >
        {!! $errors->first('trabajador_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('informe_id') ? 'has-error' : ''}}">
    <label for="informe_id" class="col-md-4 control-label">{{ 'Informe Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="informe_id" type="number" id="informe_id" value="{{ $cuotum->informe_id or ''}}" >
        {!! $errors->first('informe_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
