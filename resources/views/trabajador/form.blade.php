<div class="form-group {{ $errors->has('nombre') ? 'has-error' : ''}}">
    <label for="nombre" class="col-md-4 control-label">{{ 'Nombre' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="nombre" type="text" id="nombre" value="{{ $trabajador->nombre or ''}}" required>
        {!! $errors->first('nombre', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('celular') ? 'has-error' : ''}}">
    <label for="celular" class="col-md-4 control-label">{{ 'Celular' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="celular" type="number" id="celular" value="{{ $trabajador->celular or ''}}" required>
        {!! $errors->first('celular', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('latitud') ? 'has-error' : ''}}">
    <label for="latitud" class="col-md-4 control-label">{{ 'Latitud' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="latitud" type="text" id="latitud" value="{{ $trabajador->latitud or ''}}" >
        {!! $errors->first('latitud', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('longitud') ? 'has-error' : ''}}">
    <label for="longitud" class="col-md-4 control-label">{{ 'Longitud' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="longitud" type="text" id="longitud" value="{{ $trabajador->longitud or ''}}" >
        {!! $errors->first('longitud', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="col-md-4 control-label">{{ 'Password' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="password" type="text" id="password" value="{{ $trabajador->password or ''}}" >
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('habilitado') ? 'has-error' : ''}}">
    <label for="habilitado" class="col-md-4 control-label">{{ 'Habilitado' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="habilitado" type="number" id="habilitado" value="{{ $trabajador->habilitado or ''}}" >
        {!! $errors->first('habilitado', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
