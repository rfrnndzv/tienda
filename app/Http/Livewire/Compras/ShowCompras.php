<?php

namespace App\Http\Livewire\Compras;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Tarifa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCompras extends Component
{
    use WithPagination;

    public $fecha_1, $fecha_2, $cant = '10', $producto_id, $precio;
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
    ];

    protected $rules = [
        'compra.producto_id' => 'required',
        'compra.cantidad' => 'required|numeric',
        'compra.pago' => 'required',
    ];

    public function mount(){
        date_default_timezone_set('America/La_Paz');
        $this->fecha_1 = Carbon::now()->format('Y-m-d 00:00:00');
        $this->fecha_2 = Carbon::now()->format('Y-m-d 23:59:59');
    }

    public function loadCompras(){
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
            $compras = Compra::whereBetween('created_at', [$this->fecha_1, $this->fecha_2])
                            ->orderby($this->sort, $this->direction)
                            ->paginate($this->cant);
            $productos = Producto::all();
            $this->subtotal();
        } else {
            $compras = [];
            $productos = [];
        }     

        return view('livewire.compras.show-compras', compact('compras', 'productos'));
    }

    public function edit(Compra $compra){
        
        $this->compra = $compra;

        $producto_id = $this->compra->producto_id;

        $this->open_edit = true;
    }

    public function update(){
        $this->validate();
        
        $this->compra->user_id = Auth::user()->id;

        $this->compra->save();

        $this->reset(['open_edit']);
        $this->emit('alert', 'La compra se actualizó satisfactoriamente.');
    }

    public function delete(Compra $compra){
        $compra->delete();
    }

    public function subtotal(){
        $compras = Compra::whereBetween('created_at', [$this->fecha_1, $this->fecha_2])
                            ->get();

        $precio = 0;
        $this->subtotal = 0;

        foreach ($compras as $compra) {
            foreach ($compra->producto->tarifa as $tarifa) {
                if ($compra->created_at >= $tarifa->created_at) {
                    $precio = $tarifa->compra;
                }
            }
            $this->subtotal = $this->subtotal + $tarifa->compra * $compra->cantidad;
        }
    }
}
