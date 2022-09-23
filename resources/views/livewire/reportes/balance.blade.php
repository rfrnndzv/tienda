<div>
    <x-slot name="header" class="shadow">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Balance') }}
        </h2>
    </x-slot>

    <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="px-6 py-4 flex">
            <x-jet-label>Desde: </x-jet-label>
            <x-jet-input class="flex-1 mx-4" type="date" wire:model="fecha_1" />
            <x-jet-label>hasta: </x-jet-label>
            <x-jet-input class="flex-1 mx-4" type="datetime-local" wire:model="fecha_2" />
        </div>

        <x-jet-label>Utilidad: {{ $balance[0] + $balance[4] - ($balance[2] + $balance[3]) }}</x-jet-label>

        {{-- Tabla de Productos --}}
        <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <x-table>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th></th>
                            <th>DEBE</th>
                            <th>HABER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-left">Capital en Producto</th>
                            <td></td>
                            <td class="text-right">{{ $balance[0] }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Compras</th>
                            <td class="text-right">{{ $balance[2] }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th class="text-left">Compras (Prestamos)</th>
                            <td class="text-right">{{ $balance[3] }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th class="text-left">Capital Efectivo (Ventas)</th>
                            <td></td>
                            <td class="text-right">{{ $balance[4] }}</td>
                        </tr>

                        <tr>
                            <th class="text-left"></th>
                            <td class="text-right font-bold"> {{ $balance[2] + $balance[3] }} </td>
                            <td class="text-right font-bold"> {{ $balance[0] + $balance[4] }} </td>
                        </tr>

                    </tbody>
                </table>

            </x-table>
        </div>

    </div>

</div>
