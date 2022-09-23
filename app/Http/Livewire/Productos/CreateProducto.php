<?php

namespace App\Http\Livewire\Productos;

use App\Models\Producto;
use App\Models\Tarifa;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProducto extends Component
{
    use WithFileUploads;

    public $open = false;
    public $id_producto, $nombre, $volumen, $categoria, $imagen, $identificador;
    public $compra, $venta, $producto_id;

    public function mount(){
        $this->identificador = rand();
    }

    protected $rules = [
        'id_producto' => 'required|max:50',
        'nombre' => 'required|max:80',
        'volumen' => 'required|max:10',
        'categoria' => 'required|max:50',
        'imagen' => 'required|image|max:1024',
        'compra' => 'required|numeric',
        'venta' => 'required|numeric',
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function save(){
        
        $this->validate();

        $imagen = $this->imagen->store('productos');

        $producto = Producto::create([
            'id' => $this->id_producto,
            'nombre' => $this->nombre,
            'volumen' => $this->volumen,
            'categoria' => $this->categoria,
            'imagen' => $imagen,
        ]);

        Tarifa::create([
            'compra' => $this->compra,
            'venta' => $this->venta,
            'producto_id' => $this->id_producto,
        ]);

        $this->reset(['open', 'id_producto', 'nombre', 'volumen', 'categoria', 'imagen']);

        $this->identificador = rand();
        
        $this->emitTo('productos.show-productos','render');
        $this->emit('alert', 'El producto se creo satisfactoriamente.');
    }

    public function render()
    {
        return view('livewire.productos.create-producto');
    }
}
