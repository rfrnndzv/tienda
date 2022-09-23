<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad',
        'pago',
        'producto_id',
        'user_id',
    ];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
