<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Gestión de servicios
                </h2>
                <p class="text-sm text-gray-500">
                    Administra los servicios disponibles para la atención de ciudadanos.
                </p>
            </div>

            @can('servicios.crear')
                <a href="{{ route('admin.servicios.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    + Nuevo servicio
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl bg-white p-5 shadow-sm">
                <form method="GET"
                      action="{{ route('admin.servicios.index') }}"
                      class="flex flex-col gap-3 sm:flex-row">

                    <input
                        type="text"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        placeholder="Buscar por código o nombre del servicio..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Buscar
                        </button>

                        <a href="{{ route('admin.servicios.index') }}"
                           class="rounded-lg border border-gray-300 px-5 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">
                        Servicios registrados
                    </h3>
                    <p class="text-sm text-gray-500">
                        Total encontrados: {{ $servicios->total() }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Descripción</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Estado</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($servicios as $servicio)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $servicio->id }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $servicio->code }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $servicio->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $servicio->description
                                            ? \Illuminate\Support\Str::limit($servicio->description, 70)
                                            : 'Sin descripción' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if ($servicio->is_active)
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @can('servicios.editar')
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('admin.servicios.edit', $servicio) }}"
                                                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                                    Editar
                                                </a>

                                                <form action="{{ route('admin.servicios.toggle-status', $servicio) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Deseas cambiar el estado de este servicio?');">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="text-sm font-semibold {{ $servicio->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                                        {{ $servicio->is_active ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No se encontraron servicios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $servicios->links() }}
                </div>
            </div>

            <div>
                <a href="{{ route('dashboard') }}"
                   class="text-sm font-medium text-gray-600 hover:text-indigo-600">
                    ← Volver al panel principal
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
