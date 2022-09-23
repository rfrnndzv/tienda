<?php

use App\Http\Livewire\Compras\ShowCompras;
use App\Http\Livewire\Productos\ShowProductos;
use App\Http\Livewire\Reportes\Balance;
use App\Http\Livewire\Ventas\ShowVentas;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->get('/dashboard', ShowProductos::class)->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->get('/compras', ShowCompras::class)->name('compras.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->get('/ventas', ShowVentas::class)->name('ventas.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->get('/balance', Balance::class)->name('balance.index');