<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ShowVentas extends Component
{
    use WithPagination;
    
    public $search, $fecha_1, $fecha_2, $cant = '10', $producto_id;
    public $sort = 'created_at';
    public $direction = 'desc';
    public $open_edit = false;
    public $readyToLoad = false;
    public $subtotal;

    protected $listeners = ['render', 'delete'];

    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'venta.producto_id' => 'required',
        'venta.cantidad' => 'required|numeric',
    ];

    public function mount(){
        date_default_timezone_set('America/La_Paz');
        $this->fecha_1 = Carbon::now()->format('Y-m-d 00:00:00');
        $this->fecha_2 = Carbon::now()->format('Y-m-d 23:59:59');
    }

    public function loadVentas(){
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

    public function render()
    {
        if ($this->readyToLoad) {
            $ventas = Venta::whereBetween('created_at', [$this->fecha_1, $this->fecha_2])
                            ->orderby($this->sort, $this->direction)
                            ->paginate($this->cant);
            $productos = Producto::all();
            $this->subtotal();
        } else {
            $ventas = [];
            $productos = [];
        }     

        return view('livewire.ventas.show-ventas', compact('ventas', 'productos'));
    }

    public function edit(Venta $venta){
        
        $this->venta = $venta;

        $producto_id = $this->venta->producto_id;

        $this->open_edit = true;
    }

    public function update(){
        $this->validate();
        
        $this->venta->user_id = Auth::user()->id;

        $this->venta->save();

        $this->reset(['open_edit']);
        $this->emit('alert', 'La venta se actualizó satisfactoriamente.');
    }

    public function delete(Venta $venta){
        $venta->delete();
    }

    public function subtotal(){
        $ventas = Venta::whereBetween('created_at', [$this->fecha_1, $this->fecha_2])
                        ->get();        

        $precio = 0;
        $this->subtotal = 0;

        foreach ($ventas as $venta) {
            foreach ($venta->producto->tarifa as $tarifa) {
                if ($venta->created_at >= $tarifa->created_at) {
                    $precio = $tarifa->venta;
                }
            }
            $this->subtotal = $this->subtotal + $tarifa->venta * $venta->cantidad;
        }
    }
}
