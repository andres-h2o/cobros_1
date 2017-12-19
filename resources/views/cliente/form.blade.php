<div class="form-group {{ $errors->has('nombre') ? 'has-error' : ''}}">
    <label for="nombre" class="col-md-4 control-label">{{ 'Nombre' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="nombre" type="text" id="nombre" value="{{ $cliente->nombre or ''}}" required>
        {!! $errors->first('nombre', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('celular') ? 'has-error' : ''}}">
    <label for="celular" class="col-md-4 control-label">{{ 'Celular' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="celular" type="number" id="celular" value="{{ $cliente->celular or ''}}" >
        {!! $errors->first('celular', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('latitud') ? 'has-error' : ''}}">
    <label for="latitud" class="col-md-4 control-label">{{ 'Latitud' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="latitud" type="text" id="latitud" value="{{ $cliente->latitud or ''}}" required>
        {!! $errors->first('latitud', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('longitud') ? 'has-error' : ''}}">
    <label for="longitud" class="col-md-4 control-label">{{ 'Longitud' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="longitud" type="text" id="longitud" value="{{ $cliente->longitud or ''}}" required>
        {!! $errors->first('longitud', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('conPrestamo') ? 'has-error' : ''}}">
    <label for="conPrestamo" class="col-md-4 control-label">{{ 'Conprestamo' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="conPrestamo" type="number" id="conPrestamo" value="{{ $cliente->conPrestamo or ''}}" >
        {!! $errors->first('conPrestamo', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
