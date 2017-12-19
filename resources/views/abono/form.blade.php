<div class="form-group {{ $errors->has('fecha') ? 'has-error' : ''}}">
    <label for="fecha" class="col-md-4 control-label">{{ 'Fecha' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha" type="date" id="fecha" value="{{ $abono->fecha or ''}}" >
        {!! $errors->first('fecha', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('monto') ? 'has-error' : ''}}">
    <label for="monto" class="col-md-4 control-label">{{ 'Monto' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="monto" type="number" id="monto" value="{{ $abono->monto or ''}}" >
        {!! $errors->first('monto', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('credito_id') ? 'has-error' : ''}}">
    <label for="credito_id" class="col-md-4 control-label">{{ 'Credito Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="credito_id" type="number" id="credito_id" value="{{ $abono->credito_id or ''}}" >
        {!! $errors->first('credito_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('informe_id') ? 'has-error' : ''}}">
    <label for="informe_id" class="col-md-4 control-label">{{ 'Informe Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="informe_id" type="number" id="informe_id" value="{{ $abono->informe_id or ''}}" >
        {!! $errors->first('informe_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
