<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateVenta extends Component
{
    public $open = false;
    public $fecha, $producto_id, $cantidad, $nombre_producto;

    public function mount(){
        date_default_timezone_set('America/La_Paz');
        $this->fecha = Carbon::now()->format('Y/m/d H:i:s');
    }

    protected $rules = [
        'producto_id' => 'required',
        'cantidad' => 'required|numeric',
    ]; 

    public function updated($propertyFecha){
        $this->validateOnly($propertyFecha);
    }

    public function save(){
        
        $this->validate();

        Venta::create([
            'producto_id' => $this->producto_id,
            'cantidad' => $this->cantidad,
            'user_id' => Auth::user()->id,
        ]);

        $this->fecha = Carbon::now()->format('Y/m/d H:i:s');
        $this->reset(['open', 'producto_id', 'cantidad']);        
        $this->emitTo('ventas.show-ventas','render');
        $this->emit('alert', 'La venta se realizó satisfactoriamente.');
    }

    public function render()
    {
        $productos = Producto::all();
        
        return view('livewire.ventas.create-venta', compact('productos'));
    }
}
