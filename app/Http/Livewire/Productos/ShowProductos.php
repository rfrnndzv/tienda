<?php

namespace App\Http\Livewire\Productos;

use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowProductos extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '', $producto, $imagen, $identificador, $cant = '10';
    public $sort = 'created_at';
    public $direction = 'desc';
    public $open_edit = false;
    public $readyToLoad = false;

    protected $listeners = ['render', 'delete'];

    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => ''],
    ];

    public function mount(){
        $this->identificador = rand();
        $this->producto = new Producto();
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    protected $rules = [
        'producto.id' => 'required|max:50',
        'producto.nombre' => 'required|max:80',
        'producto.volumen' => 'required|max:10',
        'producto.categoria' => 'required|max:50',
    ];    

    public function render()
    {
        if ($this->readyToLoad) {
            $productos = Producto::where('id', 'like', '%' . $this->search . '%')
                            ->orwhere('categoria', 'like', '%' . $this->search . '%')
                            ->orderby($this->sort, $this->direction)
                            ->paginate($this->cant);
        } else {
            $productos = [];
        }        
        
        return view('livewire.productos.show-productos', compact('productos'));
    }

    public function loadProductos(){
        $this->readyToLoad = true;
    }

    public function order($sort){
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
            
        } else {
            $this->sort = $sort;
        }
        
    }

    public function edit(Producto $producto){
        $this->producto = $producto;
        $this->open_edit = true;
    }

    public function update(){
        $this->validate();
        if ($this->imagen) {
            Storage::delete([$this->producto->imagen]);
            $this->producto->imagen = $this->imagen->store('productos');
        }
        $this->producto->save();

        $this->reset(['open_edit', 'imagen']);
        $this->identificador = rand();
        $this->emit('alert', 'El producto se actualizó satisfactoriamente.');
    }

    public function delete(Producto $producto){
        $producto->delete();
    }
}
