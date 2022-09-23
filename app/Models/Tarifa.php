<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra',
        'venta',
        'producto_id',
    ];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
