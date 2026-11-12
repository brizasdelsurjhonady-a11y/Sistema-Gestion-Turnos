<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de mi turno
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-8 text-center shadow">

                <p class="mb-2 text-gray-500">
                    Tu número de atención es
                </p>

                <h3 class="mb-6 text-5xl font-extrabold tracking-wide text-blue-700">
                    {{ $ticket->ticket_number }}
                </h3>

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

                <div class="mb-8 inline-block rounded-full bg-blue-100 px-5 py-2 font-semibold text-blue-800">
                    {{ $statuses[$ticket->status] ?? $ticket->status }}
                </div>

                <div class="space-y-4 border-t border-gray-200 pt-6 text-left">

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Servicio</span>
                        <strong class="text-right text-gray-800">
                            {{ $ticket->service->name }}
                        </strong>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Fecha</span>
                        <strong class="text-gray-800">
                            {{ $ticket->ticket_date->format('d/m/Y') }}
                        </strong>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Ventanilla</span>
                        <strong class="text-gray-800">
                            {{ $ticket->counter?->name ?? 'Pendiente de asignación' }}
                        </strong>
                    </div>

                    @if ($ticket->observations)
                        <div class="border-t border-gray-200 pt-4">
                            <span class="text-gray-500">Observaciones</span>
                            <p class="mt-1 text-gray-800">
                                {{ $ticket->observations }}
                            </p>
                        </div>
                    @endif

                </div>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('ciudadano.turnos.index') }}"
                       class="rounded-lg border border-gray-300 px-5 py-3 text-gray-700 hover:bg-gray-50">
                        Mis turnos
                    </a>

                    <a href="{{ route('ciudadano.turnos.create') }}"
                       class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                        Solicitar otro turno
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
