<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel del operador
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->has('ticket'))
                <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                    {{ $errors->first('ticket') }}
                </div>
            @endif

            {{-- MIS VENTANILLAS --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800">
                    Mis ventanillas
                </h3>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @forelse ($counters as $counter)
                        <div class="border rounded-lg p-4">
                            <p class="font-bold text-gray-800">
                                {{ $counter->code }} - {{ $counter->name }}
                            </p>

                            <p class="text-sm text-gray-600 mt-1">
                                Servicio:
                                {{ $counter->service->name ?? 'Sin servicio' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-600">
                            No tienes ventanillas activas asignadas.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- LISTADO DE TURNOS --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Gestión de turnos
                        </h3>

                        <p class="text-sm text-gray-600">
                            Turnos pendientes y turnos asignados a tu ventanilla.
                        </p>
                    </div>

                    <span class="text-sm font-semibold text-blue-700">
                        {{ $tickets->total() }} turnos
                    </span>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Número
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Ciudadano
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Servicio
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Ventanilla
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Fecha
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Estado
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse ($tickets as $ticket)
                                <tr>
                                    {{-- NÚMERO --}}
                                    <td class="px-4 py-3 font-bold text-gray-900">
                                        {{ $ticket->ticket_number }}
                                    </td>

                                    {{-- CIUDADANO --}}
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $ticket->user->name ?? 'Sin usuario' }}
                                    </td>

                                    {{-- SERVICIO --}}
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $ticket->service->name ?? 'Sin servicio' }}
                                    </td>

                                    {{-- VENTANILLA --}}
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $ticket->counter->code ?? 'Pendiente' }}
                                    </td>

                                    {{-- FECHA --}}
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $ticket->ticket_date->format('d/m/Y') }}
                                    </td>

                                    {{-- ESTADO --}}
                                    <td class="px-4 py-3">
                                        @if ($ticket->status === 'waiting')
                                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold">
                                                En espera
                                            </span>

                                        @elseif ($ticket->status === 'called')
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                                                Llamado
                                            </span>

                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="px-2 py-1 rounded bg-purple-100 text-purple-800 text-xs font-semibold">
                                                En atención
                                            </span>

                                        @elseif ($ticket->status === 'completed')
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">
                                                Atendido
                                            </span>

                                        @elseif ($ticket->status === 'no_show')
                                            <span class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">
                                                No se presentó
                                            </span>

                                        @elseif ($ticket->status === 'cancelled')
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs font-semibold">
                                                Cancelado
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-4 py-3">

                                        @if ($ticket->status === 'waiting')

                                            @can('turnos.llamar')
                                                <form
                                                    action="{{ route('operador.turnos.call', $ticket) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('¿Deseas llamar al turno {{ $ticket->ticket_number }}?')"
                                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                                    >
                                                        Llamar
                                                    </button>
                                                </form>
                                            @endcan

                                        @elseif ($ticket->status === 'called')

                                            @can('turnos.iniciar')
                                                <form
                                                    action="{{ route('operador.turnos.start', $ticket) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('¿Iniciar la atención de {{ $ticket->ticket_number }}?')"
                                                        class="rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-600"
                                                    >
                                                        Iniciar atención
                                                    </button>
                                                </form>
                                            @endcan

                                        @elseif ($ticket->status === 'in_progress')

                                            @can('turnos.finalizar')
                                                <form
                                                    action="{{ route('operador.turnos.complete', $ticket) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('¿Finalizar la atención de {{ $ticket->ticket_number }}?')"
                                                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700"
                                                    >
                                                        Finalizar atención
                                                    </button>
                                                </form>
                                            @endcan

                                        @elseif ($ticket->status === 'completed')
                                            <span class="text-sm font-semibold text-green-700">
                                                Atención terminada
                                            </span>
                                        @endif

                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        No hay turnos disponibles.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
