<div wire:init="loadVentas">
    <x-slot name="header" class="shadow">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ventas') }}
        </h2>
    </x-slot>

    {{-- Tabla de Ventas --}}
    <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <x-table>

            <div class="px-6 py-4 flex items-center">
                <div class="flex-1 items-center">
                    <span>Mostrar</span>
                    <select wire:model="cant" class="mx-2 form-control">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entradas</span>
                </div>
                @livewire('ventas.create-venta')
            </div>

            <div class="px-6 py-4 flex items-center">
                <x-jet-label>Desde: </x-jet-label>
                <x-jet-input class="flex-1 mx-4" type="datetime-local" wire:model="fecha_1" />
                <x-jet-label>hasta: </x-jet-label>
                <x-jet-input class="flex-1 mx-4" type="datetime-local" wire:model="fecha_2" />
            </div>

            <div wire:loading wire:target="loadVentas" class="flex items-center">
                <img src="{{ asset('storage/img/progress.gif') }}">
            </div>

            @if (count($ventas))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="cursor-pointer" wire:click="order('created_at')">FECHA
                                @if ($sort == 'created_at')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('producto_id')">PRODUCTO
                                @if ($sort == 'producto_id')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('cantidad')">CANTIDAD
                                @if ($sort == 'cantidad')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th>TOTAL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->created_at }}</td>
                                <td class="px-6 py-4">{{ $item->producto->nombre }}</td>
                                <td class="px-6 py-4">{{ $item->cantidad }}</td>
                                <td class="px-6 py-4">{{ $item->cantidad * $item->producto->tarifa[$item->producto->tarifa->count()-1]->venta }}</td>
                                <td class="px-6 py-4 flex">
                                    <x-jet-button class="bg-sky-500 mx-2" wire:click="edit({{ $item }})">
                                        <i class="fas fa-edit"></i>
                                    </x-jet-button>

                                    <x-jet-button class="bg-red-800" wire:click="$emit('deleteVenta', {{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </x-jet-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($ventas->hasPages())
                    <div class="px-6 py-3">
                        {{ $ventas->links() }}
                    </div>
                @endif

                <div class="px-6 py-4 flex">
                    <x-jet-label class="mr-4">Sub-Total: </x-jet-label>
                    <x-jet-label>Bs. {{ $subtotal }}</x-jet-label>
                </div>

            @else
                <x-jet-label>No existe registros.</x-jet-layout>
            @endif

        </x-table>
    </div>

    {{-- Modal para Edici??n --}}
    <x-jet-dialog-modal wire:model="open_edit">
        <x-slot name="title">
            Editar Venta
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label value="Fecha*" />
                <x-jet-input type="text" class="w-1/4" disabled="true"/>
            </div>

            <div class="mb-4">
                <x-jet-label value="Producto*" />
                <select class="js-example-basic-single" wire:model="ventas.producto_id" style="width: 100%">
                    @foreach ($productos as $item)
                        @if ($item->id == $producto_id)
                            <option value="{{ $item->id }}" selected>{{ $item->nombre }}</option>        
                        @else
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endif
                    @endforeach
                </select>
                <x-jet-input-error for="ventas.producto_id" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Cantidad*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="ventas.cantidad" />
                <x-jet-input-error for="cantidad" />
            </div>

        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button class="mr-4" wire:click="$set('open_edit',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('js')

        <script>
            Livewire.on('deleteVenta', compraid => {
                Swal.fire({
                    title: '??Est?? seguro(a)?',
                    text: "Esta acci??n no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '??Si, borrar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('ventas.show-ventas', 'delete', compraid)

                        Swal.fire(
                            '??Borrado!',
                            'La compra ha sido borrada.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
