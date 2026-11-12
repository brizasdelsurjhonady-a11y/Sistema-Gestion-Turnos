<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de ventanillas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Ventanillas registradas
                    </h3>
                    <p class="text-sm text-gray-600">
                        Administra las ventanillas, servicios y operadores asignados.
                    </p>
                </div>

                <a href="{{ route('admin.ventanillas.create') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-center text-white hover:bg-indigo-700">
                    + Nueva ventanilla
                </a>
            </div>

            <div class="mb-6 rounded-lg bg-white p-4 shadow-sm">
                <form method="GET" action="{{ route('admin.ventanillas.index') }}"
                      class="flex flex-col gap-3 sm:flex-row">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por código, nombre o servicio..."
                           class="w-full rounded-lg border-gray-300">

                    <button type="submit"
                            class="rounded-lg bg-gray-800 px-5 py-2 text-white hover:bg-gray-700">
                        Buscar
                    </button>

                    <a href="{{ route('admin.ventanillas.index') }}"
                       class="rounded-lg border border-gray-300 px-5 py-2 text-center text-gray-700 hover:bg-gray-50">
                        Limpiar
                    </a>
                </form>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h4 class="font-semibold text-gray-800">
                        Total encontradas: {{ $ventanillas->total() }}
                    </h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Ventanilla</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Operador</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($ventanillas as $ventanilla)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $ventanilla->id }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                        {{ $ventanilla->code }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $ventanilla->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $ventanilla->service?->name ?? 'Sin servicio' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $ventanilla->user?->name ?? 'Sin asignar' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($ventanilla->is_active)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                Activa
                                            </span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-start gap-2">
                                            <a href="{{ route('admin.ventanillas.edit', $ventanilla) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.ventanillas.toggle-status', $ventanilla) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="text-sm font-medium {{ $ventanilla->is_active ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $ventanilla->is_active ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        No hay ventanillas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $ventanillas->links() }}
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('dashboard') }}"
                   class="text-sm text-indigo-600 hover:underline">
                    ← Volver al panel principal
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
