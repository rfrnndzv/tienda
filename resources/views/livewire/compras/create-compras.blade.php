<div>
    <x-jet-button wire:click="$set('open', true)">
        Realizar Compra
    </x-jet-button>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Nueva Compra
        </x-slot>

        <x-slot name="content">

            <div class="mb-4">
                <x-jet-label value="Fecha*" />
                <x-jet-input type="text" class="" wire:model="fecha" disabled="true"/>
                <x-jet-input-error for="fecha" />
            </div>

            <div class="mb-4" wire:ignore>
                <x-jet-label value="Producto*" />
                <select class="js-example-basic-single" wire:model="producto_id" style="width: 100%">
                    <option value="" selected>Seleccione un producto</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre . ' - Bs. ' . $producto->tarifa[$producto->tarifa->count()-1]->compra }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="producto_id" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Cantidad*" />
                <x-jet-input type="number" class="w-full" wire:model="cantidad" />
                <x-jet-input-error for="cantidad" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Pago*" />
                <select class="w-1/4 form-select" wire:model="pago">
                    <option value="" selected>Seleccione una opción</option>
                    <option value="debito">Contado</option>
                    <option value="credito">Prestamo</option>
                </select>
                <x-jet-input-error for="pago" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-4" wire:click="$set('open',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                class="disabled:opacity-25">
                Comprar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                $('.js-example-basic-single').select2()
                $('.js-example-basic-single').on('change', function(){
                    @this.set('producto_id', this.value)
                })
            })

            Livewire.on('alert', function(message) {
                Swal.fire(
                    'Muy Bien!',
                    message,
                    'success'
                )
            })
        </script>
    @endpush
</div>
