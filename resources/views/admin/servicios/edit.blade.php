<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Editar servicio
            </h2>
            <p class="text-sm text-gray-500">
                Modifica los datos de {{ $servicio->name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                <form action="{{ route('admin.servicios.update', $servicio) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        {{-- Código --}}
                        <div>
                            <label for="code" class="mb-2 block text-sm font-medium text-gray-700">
                                Código del servicio
                            </label>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code', $servicio->code) }}"
                                required
                                maxlength="20"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nombre del servicio
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $servicio->name) }}"
                                required
                                maxlength="150"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                                Descripción
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $servicio->description) }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado actual --}}
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="text-sm font-medium text-gray-700">
                                Estado actual
                            </p>

                            @if ($servicio->is_active)
                                <span class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Activo
                                </span>
                            @else
                                <span class="mt-2 inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Inactivo
                                </span>
                            @endif

                            <p class="mt-2 text-xs text-gray-500">
                                Puedes cambiar el estado desde el listado de servicios.
                            </p>
                        </div>

                        {{-- Botones --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                            <a href="{{ route('admin.servicios.index') }}"
                               class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>

                            <button
                                type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                Guardar cambios
                            </button>

                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
