<div>
    <x-jet-button wire:click="$set('open', true)">
        Crear Producto
    </x-jet-button>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Nuevo Producto
        </x-slot>

        <x-slot name="content">

            <div wire:loading wire:target="imagen"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado.</span>
            </div>

            @if ($imagen)
                <img class="mb-4" src="{{ $imagen->temporaryUrl() }}" alt="">
            @endif

            <div class="mb-4">
                <x-jet-label value="Código*" />
                <x-jet-input type="text" class="w-full" wire:model="id_producto" />
                <x-jet-input-error for="id_producto" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Nombre*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="nombre" />
                <x-jet-input-error for="nombre" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Volumen*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="volumen" />
                <x-jet-input-error for="volumen" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Categoria*" />
                <x-jet-input type="text" class="w-full" wire:model.defer="categoria" />
                <x-jet-input-error for="categoria" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Imagen" />
                <x-jet-input type="file" class="w-full" wire:model.defer="imagen" id="{{ $identificador }}" />
                <x-jet-input-error for="imagen" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Precio de Compra" />
                <x-jet-input type="text" class="w-full" wire:model="compra" />
                <x-jet-input-error for="compra" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Precio de Venta" />
                <x-jet-input type="text" class="w-full" wire:model="venta" />
                <x-jet-input-error for="venta" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-4" wire:click="$set('open',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" wire:target="save, imagen"
                class="disabled:opacity-25">
                Crear
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('js')

        <script>
            Livewire.on('alert', function(message) {
                Swal.fire(
                    'Muy Bien!',
                    message,
                    'success'
                )
            });
        </script>
    @endpush
</div>
