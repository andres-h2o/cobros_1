<div class="form-group {{ $errors->has('fecha') ? 'has-error' : ''}}">
    <label for="fecha" class="col-md-4 control-label">{{ 'Fecha' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha" type="date" id="fecha" value="{{ $movimiento->fecha or ''}}" >
        {!! $errors->first('fecha', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('monto') ? 'has-error' : ''}}">
    <label for="monto" class="col-md-4 control-label">{{ 'Monto' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="monto" type="number" id="monto" value="{{ $movimiento->monto or ''}}" >
        {!! $errors->first('monto', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('tipo') ? 'has-error' : ''}}">
    <label for="tipo" class="col-md-4 control-label">{{ 'Tipo' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="tipo" type="text" id="tipo" value="{{ $movimiento->tipo or ''}}" >
        {!! $errors->first('tipo', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('balance_id') ? 'has-error' : ''}}">
    <label for="balance_id" class="col-md-4 control-label">{{ 'Balance Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="balance_id" type="number" id="balance_id" value="{{ $movimiento->balance_id or ''}}" >
        {!! $errors->first('balance_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
