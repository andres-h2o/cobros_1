<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuotum extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cuotas';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['monto', 'fecha_pago', 'estado', 'credito_id', 'trabajador_id', 'informe_id'];

    
}
