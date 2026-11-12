<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Gestión de usuarios
                </h2>
                <p class="text-sm text-gray-500">
                    Administra las cuentas y los roles del sistema.
                </p>
            </div>

            @can('usuarios.crear')
                <a href="{{ route('admin.usuarios.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    + Nuevo usuario
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Mensajes --}}
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

            {{-- Buscador --}}
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <form method="GET"
                      action="{{ route('admin.usuarios.index') }}"
                      class="flex flex-col gap-3 sm:flex-row">

                    <input
                        type="text"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        placeholder="Buscar por nombre o correo electrónico..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Buscar
                        </button>

                        <a href="{{ route('admin.usuarios.index') }}"
                           class="rounded-lg border border-gray-300 px-5 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla de usuarios --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            Usuarios registrados
                        </h3>
                        <p class="text-sm text-gray-500">
                            Total encontrados: {{ $usuarios->total() }}
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Correo electrónico
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Rol
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($usuarios as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $usuario->id }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $usuario->name }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $usuario->email }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @forelse ($usuario->roles as $rol)
                                            <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                {{ $rol->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">
                                                Sin rol asignado
                                            </span>
                                        @endforelse
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-3">

                                            @can('usuarios.editar')
                                                <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                                    Editar
                                                </a>
                                            @endcan

                                            @can('usuarios.eliminar')
                                                @if (auth()->id() !== $usuario->id)
                                                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="text-sm font-semibold text-red-600 hover:text-red-900">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No se encontraron usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $usuarios->links() }}
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
