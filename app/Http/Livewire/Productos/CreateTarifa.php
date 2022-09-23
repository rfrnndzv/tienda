<?php

namespace App\Http\Livewire\Productos;

use App\Models\Producto;
use App\Models\Tarifa;
use Livewire\Component;

class CreateTarifa extends Component
{
    public $open_tarifa = false;
    public $producto, $tarifa, $compra, $venta, $producto_id;

    protected $rules = [
        'compra' => 'required|numeric',
        'venta' => 'required|numeric',
    ];

    public function updated($propertyCompra){
        $this->validateOnly($propertyCompra);
    }

    public function save(){
        $this->validate();

        Tarifa::create([
            'compra' => $this->compra,
            'venta' => $this->venta,
            'producto_id' => $this->producto->id,
        ]);

        $this->reset(['open_tarifa', 'compra', 'venta', 'producto_id']);
        $this->emit('alert', 'La tirifa se actualizó satisfactoriamente.');
    }

    public function render()
    {
        return view('livewire.productos.create-tarifa');
    }
}
