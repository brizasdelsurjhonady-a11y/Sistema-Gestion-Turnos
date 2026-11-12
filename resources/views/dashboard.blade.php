<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-sm font-medium text-indigo-600">SISTEMA DE GESTIÓN DE TURNOS</p>
            <h2 class="text-2xl font-bold text-gray-800">
                Panel de administración
            </h2>
            <p class="text-sm text-gray-500">
                Gestiona y supervisa las operaciones del sistema.
            </p>
        </div>
    </x-slot>

    @php
        $usuario = auth()->user();
        $roles = $usuario->getRoleNames();
        $totalPermisos = $usuario->getAllPermissions()->count();
        $rolPrincipal = $roles->first() ?? 'Sin rol asignado';
    @endphp

    <div class="min-h-screen bg-slate-100 py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- Bienvenida --}}
            <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-blue-600 p-8 text-white shadow-lg">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-sm font-medium text-indigo-100">
                            PANEL PRINCIPAL
                        </p>

                        <h1 class="mt-2 text-3xl font-bold">
                            ¡Bienvenido, {{ $usuario->name }}!
                        </h1>

                        <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100">
                            Desde aquí podrás administrar los usuarios,
                            servicios, ventanillas y turnos de atención.
                        </p>
                    </div>

                    <div class="rounded-xl bg-white/10 p-5 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-wider text-indigo-100">
                            Tu rol actual
                        </p>
                        <p class="mt-2 text-xl font-bold">
                            {{ $rolPrincipal }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Indicadores --}}
            <section>
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">
                        Resumen de tu cuenta
                    </h3>
                    <p class="text-sm text-gray-500">
                        Información real de tu sesión y tus permisos.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Usuario --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Usuario autenticado
                                </p>
                                <p class="mt-2 text-xl font-bold text-gray-800">
                                    {{ $usuario->name }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-blue-100 p-3 text-blue-700">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 truncate text-sm text-gray-500">
                            {{ $usuario->email }}
                        </p>
                    </div>

                    {{-- Rol --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Rol asignado
                                </p>
                                <p class="mt-2 text-xl font-bold text-gray-800">
                                    {{ $rolPrincipal }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-violet-100 p-3 text-violet-700">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-500">
                            Nivel de acceso de tu cuenta.
                        </p>
                    </div>

                    {{-- Permisos --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Permisos disponibles
                                </p>
                                <p class="mt-2 text-3xl font-bold text-gray-800">
                                    {{ $totalPermisos }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-emerald-100 p-3 text-emerald-700">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5-5H4a1 1 0 00-1 1v14l5-3h12a1 1 0 001-1V6a1 1 0 00-1-1z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-500">
                            Permisos asignados a tu cuenta.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Módulos --}}
            <section>
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-gray-800">
                        Módulos del sistema
                    </h3>
                    <p class="text-sm text-gray-500">
                        Componentes principales de la plataforma.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Usuarios --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-blue-100 p-3 text-blue-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 017.75-1.25M16 3.13a4 4 0 010 7.75M12 7a4 4 0 11-8 0 4 4 0 018 0zm5 13a4 4 0 00-8 0"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Usuarios</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Administración de cuentas, roles y permisos.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                    {{-- Servicios --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-purple-100 p-3 text-purple-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Servicios</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Configuración de los servicios de atención.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                    {{-- Ventanillas --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-orange-100 p-3 text-orange-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Ventanillas</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Administración de puestos y operadores.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                    {{-- Turnos --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-emerald-100 p-3 text-emerald-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Gestión de turnos</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Registro, asignación y seguimiento de turnos.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                    {{-- Pantalla --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-cyan-100 p-3 text-cyan-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 4h18v13H3zM8 21h8m-4-4v4"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Pantalla de atención</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Visualización pública de turnos llamados.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                    {{-- Reportes --}}
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 inline-flex rounded-xl bg-rose-100 p-3 text-rose-700">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h17M8 15v-3m5 3V7m5 8v-5"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Reportes</h4>
                        <p class="mt-2 text-sm text-gray-500">
                            Estadísticas y análisis de atención.
                        </p>
                        <span class="mt-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            En desarrollo
                        </span>
                    </div>

                </div>
            </section>

            {{-- Información de cuenta --}}
            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">
                            Información de seguridad
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tu sesión está autenticada en el sistema.
                        </p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Configurar mi perfil
                    </a>
                </div>
            </section>

            <footer class="pb-4 text-center text-xs text-gray-400">
                Sistema de Gestión de Turnos · {{ date('Y') }}
            </footer>

        </div>
    </div>
</x-app-layout>