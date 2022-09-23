<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Producto;
use Carbon\Carbon;
use Livewire\Component;

class Balance extends Component
{

    /* 
    Variables de Array Balance '$balance'

    [0]capitalProductos *
    [1]capitalEfectivo
    [2]compraDebito     *
    [3]compraCredito    *
    [4]venta            *

    */

    public $balance;

    public $precio, $fecha_1, $fecha_2;
    public $cantidad;

    public function mount()
    {
        $this->fecha_1 = Carbon::now()->format('Y-m-01');
        $this->fecha_2 = Carbon::now()->format('Y-m-d 23:59:59');
    }

    public function render()
    {
        $this->balance = array(0, 0, 0, 0, 0);
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $this->cantidad = 0;

            foreach ($producto->compra as $compra) {
                $this->fecha_compra = $compra->created_at;
                if ($this->fecha_1 <= $compra->created_at && $compra->created_at <= $this->fecha_2) {
                    $this->cantidad = $this->cantidad + $compra->cantidad;
                    foreach ($producto->tarifa as $tarifa) {
                        if ($compra->created_at >= $tarifa->created_at) {
                            $this->precio = $tarifa->compra;
                        }
                    }

                    if ($compra->pago == 'debito') {
                        $this->balance[2] = $this->balance[2] + $this->precio * $compra->cantidad;
                    } else {
                        $this->balance[3] = $this->balance[3] + $this->precio * $compra->cantidad;
                    }
                }
            }

            foreach ($producto->venta as $venta) {
                if ($this->fecha_1 <= $venta->created_at && $venta->created_at <= $this->fecha_2) {
                    $this->cantidad = $this->cantidad - $venta->cantidad;
                    foreach ($producto->tarifa as $tarifa) {
                        if ($venta->created_at >= $tarifa->created_at) {
                            $this->precio = $tarifa->venta;
                        }
                    }

                    $this->balance[4] = $this->balance[4] + $this->precio * $venta->cantidad;
                }
            }

            $this->balance[0] = $this->balance[0] + $this->cantidad * $producto->tarifa[$producto->tarifa->count() - 1]->compra;
        }

        return view('livewire.reportes.balance');
    }
}
