<div>
    <x-jet-button class="bg-green-700" wire:click="$set('open_tarifa', true)">
        <i class="fas fa-usd"></i>
    </x-jet-button>

    <x-jet-dialog-modal wire:model="open_tarifa">
        <x-slot name="title">
            Nueva Tarifa del Producto
        </x-slot>
        <x-slot name="content">
            
            <div class="mb-4">
                <img src="{{ Storage::url($producto->imagen) }}" width="200" height="200">
            </div>

            <div class="mb-4">
                <x-jet-label value="{{ $producto->nombre }}" />
            </div>

            <div class="mb-4">
                <x-jet-label value="{{ $producto->volumen }}" />
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
            <x-jet-secondary-button class="mr-4" wire:click="$set('open_tarifa', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
