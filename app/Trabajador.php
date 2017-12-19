<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trabajadors';

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
    protected $fillable = ['nombre', 'celular', 'latitud', 'longitud', 'password', 'habilitado'];

    
}
