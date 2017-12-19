<div class="form-group {{ $errors->has('monto') ? 'has-error' : ''}}">
    <label for="monto" class="col-md-4 control-label">{{ 'Monto' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="monto" type="number" id="monto" value="{{ $credito->monto or ''}}" >
        {!! $errors->first('monto', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('interes') ? 'has-error' : ''}}">
    <label for="interes" class="col-md-4 control-label">{{ 'Interes' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="interes" type="number" id="interes" value="{{ $credito->interes or ''}}" >
        {!! $errors->first('interes', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('fecha') ? 'has-error' : ''}}">
    <label for="fecha" class="col-md-4 control-label">{{ 'Fecha' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="fecha" type="date" id="fecha" value="{{ $credito->fecha or ''}}" >
        {!! $errors->first('fecha', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('dias') ? 'has-error' : ''}}">
    <label for="dias" class="col-md-4 control-label">{{ 'Dias' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="dias" type="number" id="dias" value="{{ $credito->dias or ''}}" >
        {!! $errors->first('dias', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('cuota') ? 'has-error' : ''}}">
    <label for="cuota" class="col-md-4 control-label">{{ 'Cuota' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="cuota" type="number" id="cuota" value="{{ $credito->cuota or ''}}" >
        {!! $errors->first('cuota', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('acuenta') ? 'has-error' : ''}}">
    <label for="acuenta" class="col-md-4 control-label">{{ 'Acuenta' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="acuenta" type="number" id="acuenta" value="{{ $credito->acuenta or ''}}" >
        {!! $errors->first('acuenta', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('estado') ? 'has-error' : ''}}">
    <label for="estado" class="col-md-4 control-label">{{ 'Estado' }}</label>
    <div class="col-md-6">
        <div class="radio">
    <label><input name="{{ estado }}" type="radio" value="1" {{ (isset($credito) && 1 == $credito->estado) ? 'checked' : '' }}> Yes</label>
</div>
<div class="radio">
    <label><input name="{{ estado }}" type="radio" value="0" @if (isset($credito)) {{ (0 == $credito->estado) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
</div>
        {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('cliente_id') ? 'has-error' : ''}}">
    <label for="cliente_id" class="col-md-4 control-label">{{ 'Cliente Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="cliente_id" type="number" id="cliente_id" value="{{ $credito->cliente_id or ''}}" >
        {!! $errors->first('cliente_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('trabajador_id') ? 'has-error' : ''}}">
    <label for="trabajador_id" class="col-md-4 control-label">{{ 'Trabajador Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="trabajador_id" type="number" id="trabajador_id" value="{{ $credito->trabajador_id or ''}}" >
        {!! $errors->first('trabajador_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('informe_id') ? 'has-error' : ''}}">
    <label for="informe_id" class="col-md-4 control-label">{{ 'Informe Id' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="informe_id" type="number" id="informe_id" value="{{ $credito->informe_id or ''}}" >
        {!! $errors->first('informe_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
