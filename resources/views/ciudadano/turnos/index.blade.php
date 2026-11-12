<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis turnos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">
                        Historial de turnos
                    </h3>
                    <p class="mt-1 text-gray-600">
                        Consulta los turnos que has solicitado.
                    </p>
                </div>

                @can('turnos.crear')
                    <a href="{{ route('ciudadano.turnos.create') }}"
                       class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                        + Solicitar turno
                    </a>
                @endcan
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Número
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Servicio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-blue-700">
                                        {{ $ticket->ticket_number }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $ticket->service->name }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $ticket->ticket_date->format('d/m/Y') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $statuses = [
                                                'waiting' => 'En espera',
                                                'called' => 'Llamado',
                                                'in_progress' => 'En atención',
                                                'completed' => 'Finalizado',
                                                'no_show' => 'No se presentó',
                                                'cancelled' => 'Cancelado',
                                            ];
                                        @endphp

                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">
                                            {{ $statuses[$ticket->status] ?? $ticket->status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <a href="{{ route('ciudadano.turnos.show', $ticket) }}"
                                           class="font-medium text-blue-600 hover:underline">
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Todavía no tienes turnos registrados.
                                        <div class="mt-3">
                                            @can('turnos.crear')
                                                <a href="{{ route('ciudadano.turnos.create') }}"
                                                   class="font-semibold text-blue-600 hover:underline">
                                                    Solicitar mi primer turno
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $tickets->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
