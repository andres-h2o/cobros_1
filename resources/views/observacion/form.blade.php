<div class="form-group {{ $errors->has('fecha') ? 'has-error' : ''}}">
    <label for="fecha" class="col-md-4 control-label">{{ 'Fecha' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha" type="date" id="fecha" value="{{ $observacion->fecha or ''}}" >
        {!! $errors->first('fecha', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('detalle') ? 'has-error' : ''}}">
    <label for="detalle" class="col-md-4 control-label">{{ 'Detalle' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="detalle" type="text" id="detalle" value="{{ $observacion->detalle or ''}}" >
        {!! $errors->first('detalle', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('credito_id') ? 'has-error' : ''}}">
    <label for="credito_id" class="col-md-4 control-label">{{ 'Credito Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="credito_id" type="number" id="credito_id" value="{{ $observacion->credito_id or ''}}" >
        {!! $errors->first('credito_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('trabajador_id') ? 'has-error' : ''}}">
    <label for="trabajador_id" class="col-md-4 control-label">{{ 'Trabajador Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="trabajador_id" type="number" id="trabajador_id" value="{{ $observacion->trabajador_id or ''}}" >
        {!! $errors->first('trabajador_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
