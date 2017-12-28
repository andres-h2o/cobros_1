<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acuentum extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acuentas';

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
    protected $fillable = ['fecha', 'monto', 'credito_id', 'informe_id'];

    
}
