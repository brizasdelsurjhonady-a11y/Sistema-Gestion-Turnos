<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar ventanilla
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow-sm">

                <h3 class="mb-6 text-lg font-bold text-gray-800">
                    Modificar datos de la ventanilla
                </h3>

                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.ventanillas.update', $ventanilla) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label for="code" class="mb-2 block font-medium text-gray-700">
                            Código de ventanilla
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code', $ventanilla->code) }}"
                            maxlength="20"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="mb-5">
                        <label for="name" class="mb-2 block font-medium text-gray-700">
                            Nombre de la ventanilla
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $ventanilla->name) }}"
                            maxlength="100"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="mb-5">
                        <label for="service_id" class="mb-2 block font-medium text-gray-700">
                            Servicio asignado
                        </label>

                        <select
                            id="service_id"
                            name="service_id"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach ($servicios as $servicio)
                                <option
                                    value="{{ $servicio->id }}"
                                    @selected(old('service_id', $ventanilla->service_id) == $servicio->id)
                                >
                                    {{ $servicio->code }} - {{ $servicio->name }}
                                    {{ $servicio->is_active ? '' : '(Inactivo)' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="user_id" class="mb-2 block font-medium text-gray-700">
                            Operador asignado
                        </label>

                        <select
                            id="user_id"
                            name="user_id"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Sin asignar</option>

                            @foreach ($operadores as $operador)
                                <option
                                    value="{{ $operador->id }}"
                                    @selected(old('user_id', $ventanilla->user_id) == $operador->id)
                                >
                                    {{ $operador->name }} - {{ $operador->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 font-semibold text-white hover:bg-indigo-700"
                        >
                            Actualizar ventanilla
                        </button>

                        <a
                            href="{{ route('admin.ventanillas.index') }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
