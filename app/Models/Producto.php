<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombre',
        'volumen',
        'categoria',
        'imagen',
    ];

    public function tarifa(){
        return $this->hasMany(Tarifa::class);
    }

    public function compra(){
        return $this->hasMany(Compra::class);
    }

    public function venta(){
        return $this->hasMany(Venta::class);
    }
}
