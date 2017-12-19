<div class="form-group {{ $errors->has('fecha') ? 'has-error' : ''}}">
    <label for="fecha" class="col-md-4 control-label">{{ 'Fecha' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha" type="date" id="fecha" value="{{ $balance->fecha or ''}}" >
        {!! $errors->first('fecha', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('estado') ? 'has-error' : ''}}">
    <label for="estado" class="col-md-4 control-label">{{ 'Estado' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="estado" type="number" id="estado" value="{{ $balance->estado or ''}}" >
        {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('informe_id') ? 'has-error' : ''}}">
    <label for="informe_id" class="col-md-4 control-label">{{ 'Informe Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="informe_id" type="number" id="informe_id" value="{{ $balance->informe_id or ''}}" >
        {!! $errors->first('informe_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
