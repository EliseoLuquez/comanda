<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model{
    //usa la tabla que le digo
    //protected $table = 'my_table';
    protected $table = 'pedidos';
    //CAMBIO PRIMARY KEY
    //protected $primaryKey = 'flight_id';

    //DESACTIVA LOS TIMESTAMP
    //public $timestamps = false;

    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';
}