<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'informes';

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
    protected $fillable = ['fecha','fecha_cierre','estado','saldo',
        'ingresos',
        'egresos',
        'cargado',
        'prestado',
        'cobrado',
        'gastado',
        'porCobrar'];

    
}
