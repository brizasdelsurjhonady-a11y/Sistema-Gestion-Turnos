<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Solicitar un turno
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-6 shadow">

                <h3 class="mb-2 text-2xl font-bold text-gray-800">
                    Nueva solicitud
                </h3>

                <p class="mb-6 text-gray-600">
                    Selecciona el servicio que necesitas para generar tu turno.
                </p>

                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($services->isEmpty())
                    <div class="rounded-lg bg-yellow-50 p-4 text-yellow-800">
                        Actualmente no hay servicios disponibles.
                    </div>
                @else
                    <form method="POST" action="{{ route('ciudadano.turnos.store') }}">
                        @csrf

                        <div class="mb-5">
                            <label for="service_id"
                                   class="mb-2 block font-medium text-gray-700">
                                Servicio
                            </label>

                            <select name="service_id"
                                    id="service_id"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecciona un servicio</option>

                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        @selected(old('service_id') == $service->id)>
                                        {{ $service->code }} - {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="observations"
                                   class="mb-2 block font-medium text-gray-700">
                                Observaciones (opcional)
                            </label>

                            <textarea name="observations"
                                      id="observations"
                                      rows="4"
                                      maxlength="1000"
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Escribe algún detalle de tu solicitud...">{{ old('observations') }}</textarea>
                        </div>

                        <div class="flex flex-wrap justify-end gap-3">
                            <a href="{{ route('ciudadano.turnos.index') }}"
                               class="rounded-lg border border-gray-300 px-5 py-3 text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                                Generar mi turno
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
