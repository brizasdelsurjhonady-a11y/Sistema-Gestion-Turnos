<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Editar usuario
            </h2>
            <p class="text-sm text-gray-500">
                Modifica los datos de {{ $usuario->name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nombre completo
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $usuario->name) }}"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Correo --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                Correo electrónico
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $usuario->email) }}"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label for="role" class="mb-2 block text-sm font-medium text-gray-700">
                                Rol del usuario
                            </label>

                            <select
                                id="role"
                                name="role"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Seleccione un rol</option>

                                @foreach ($roles as $rol)
                                    <option value="{{ $rol }}"
                                        {{ old('role', $rolActual) === $rol ? 'selected' : '' }}>
                                        {{ $rol }}
                                    </option>
                                @endforeach
                            </select>

                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nueva contraseña --}}
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h3 class="font-semibold text-gray-800">
                                Cambiar contraseña
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Deja estos campos vacíos si no deseas cambiar la contraseña actual.
                            </p>

                            <div class="mt-4 space-y-4">

                                <div>
                                    <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                                        Nueva contraseña
                                    </label>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        minlength="12"
                                        autocomplete="new-password"
                                        placeholder="Mínimo 12 caracteres"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                                        Confirmar nueva contraseña
                                    </label>

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        minlength="12"
                                        autocomplete="new-password"
                                        placeholder="Repita la nueva contraseña"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                            <a href="{{ route('admin.usuarios.index') }}"
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
