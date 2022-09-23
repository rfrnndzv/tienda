<div wire:init="loadProductos">
    <x-slot name="header" class="shadow">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    {{-- Tabla de Productos --}}
    <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <x-table>

            <div class="px-6 py-4 flex items-center">
                <div class="flex items-center">
                    <span>Mostrar</span>
                    <select wire:model="cant" class="mx-2 form-control">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entradas</span>
                </div>
                <x-jet-input class="flex-1 mx-4" placeholder="Escriba su busqueda" type="text" wire:model="search" />
                @livewire('productos.create-producto')
            </div>

            <div wire:loading wire:target="loadProductos" class="flex items-center">
                <img src="{{ asset('storage/img/progress.gif') }}">
            </div>

            @if (count($productos))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="cursor-pointer" wire:click="order('id')">CÓDIGO
                                @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('nombre')">NOMBRE
                                @if ($sort == 'nombre')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th>CANTIDAD</th>
                            <th class="cursor-pointer" wire:click="order('volumen')">VOLUMEN
                                @if ($sort == 'volumen')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('categoria')">CATEGORÍA
                                @if ($sort == 'categoria')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->id }}</td>
                                <td class="px-6 py-4">{{ $item->nombre }}</td>

                                <?php $cantidad = 0 ?>
                                @foreach ($item->compra as $compra)
                                    <?php
                                        $cantidad = $cantidad + $compra->cantidad;
                                    ?>    
                                @endforeach

                                @foreach ($item->venta as $venta)
                                    <?php
                                        $cantidad = $cantidad - $venta->cantidad;
                                    ?>    
                                @endforeach
                                
                                <td class="px-6 py-4">{{ $cantidad }}</td>

                                <td class="px-6 py-4">{{ $item->volumen }}</td>
                                <td class="px-6 py-4">{{ $item->categoria }}</td>
                                <td class="px-6 py-4 flex">
                                    @livewire('productos.create-tarifa', ['producto' => $item], key($item->id))

                                    <x-jet-button class="bg-sky-500 mx-2" wire:click="edit({{ $item }})">
                                        <i class="fas fa-edit"></i>
                                    </x-jet-button>

                                    <x-jet-button class="bg-red-800" wire:click="$emit('deleteProducto', {{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </x-jet-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($productos->hasPages())
                    <div class="px-6 py-3">
                        {{ $productos->links() }}
                    </div>
                @endif
            @else
                <x-jet-label>No existe registros.</x-jet-layout>
            @endif

        </x-table>
    </div>

    {{-- Modal para Edición --}}
    <x-jet-dialog-modal wire:model="open_edit">
        <x-slot name="title">
            Editar Producto
        </x-slot>
        <x-slot name="content">
            <div wire:loading wire:target="imagen"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado.</span>
            </div>

            @if ($imagen)
                <img class="mb-4" src="{{ $imagen->temporaryUrl() }}" alt="">
            @else
                <img src="{{ Storage::url($producto->imagen) }}">
            @endif

            <div class="mb-4">
                <x-jet-label value="Código*" />
                <x-jet-input type="text" class="w-full" wire:model="producto.codigo" />
                <x-jet-input-error for="codigo" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Nombre*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="producto.nombre" />
                <x-jet-input-error for="nombre" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Volumen*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="producto.volumen" />
                <x-jet-input-error for="volumen" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Categoria*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="producto.categoria" />
                <x-jet-input-error for="categoria" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Imagen" />
                <x-jet-input type="file" class="w-full" wire:model.defer="imagen" id="{{ $identificador }}" />
                <x-jet-input-error for="imagen" />
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
            Livewire.on('deleteProducto', productoid => {
                Swal.fire({
                    title: '¿Está seguro(a)?',
                    text: "Esta acción no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Si, borrar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('productos.show-productos', 'delete', productoid)

                        Swal.fire(
                            '¡Borrado!',
                            'El producto ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush

</div>
