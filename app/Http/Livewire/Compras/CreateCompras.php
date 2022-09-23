<?php

namespace App\Http\Livewire\Compras;

use App\Models\Compra;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateCompras extends Component
{
    public $open = false;
    public $fecha, $producto_id, $cantidad, $pago, $nombre_producto;

    public function mount(){
        date_default_timezone_set('America/La_Paz');
        $this->fecha = Carbon::now()->format('Y/m/d H:i:s');
    }

    protected $rules = [
        'producto_id' => 'required',
        'cantidad' => 'required|numeric',
        'pago' => 'required',
    ]; 

    public function updated($propertyFecha){
        $this->validateOnly($propertyFecha);
    }

    public function save(){
        
        $this->validate();

        Compra::create([
            'producto_id' => $this->producto_id,
            'cantidad' => $this->cantidad,
            'pago' => $this->pago,
            'user_id' => Auth::user()->id,
        ]);

        $this->fecha = Carbon::now()->format('Y/m/d H:i:s');
        $this->reset(['open', 'producto_id', 'cantidad', 'pago']);        
        $this->emitTo('compras.show-compras','render');
        $this->emit('alert', 'La compra se realizó satisfactoriamente.');
    }

    public function render()
    {
        $productos = Producto::all();

        return view('livewire.compras.create-compras', compact('productos'));
    }
}
