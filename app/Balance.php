<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'balances';

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
    protected $fillable = ['fecha', 'estado', 'trabajador_id', 'informe_id'];

    
}
