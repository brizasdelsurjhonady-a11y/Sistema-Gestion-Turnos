<?php

use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Ciudadano\TicketController;
use App\Http\Controllers\Operador\TurnoController as OperadorTurnoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Módulo de administración
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
                // Consultar servicios
        Route::get('/servicios', [ServiceController::class, 'index'])
            ->middleware('permission:servicios.ver')
            ->name('servicios.index');

        // Registrar servicios
        Route::get('/servicios/crear', [ServiceController::class, 'create'])
            ->middleware('permission:servicios.crear')
            ->name('servicios.create');

                    // ==========================================
        // MÓDULO DE VENTANILLAS
        // ==========================================

        // Consultar ventanillas
        Route::get('/ventanillas', [CounterController::class, 'index'])
            ->middleware('permission:ventanillas.ver')
            ->name('ventanillas.index');

        // Registrar ventanillas
        Route::get('/ventanillas/crear', [CounterController::class, 'create'])
            ->middleware('permission:ventanillas.gestionar')
            ->name('ventanillas.create');

        Route::post('/ventanillas', [CounterController::class, 'store'])
            ->middleware('permission:ventanillas.gestionar')
            ->name('ventanillas.store');

        // Editar ventanillas
        Route::get('/ventanillas/{ventanilla}/editar', [CounterController::class, 'edit'])
            ->middleware('permission:ventanillas.gestionar')
            ->name('ventanillas.edit');

        Route::put('/ventanillas/{ventanilla}', [CounterController::class, 'update'])
            ->middleware('permission:ventanillas.gestionar')
            ->name('ventanillas.update');

        // Activar o desactivar ventanillas
        Route::patch('/ventanillas/{ventanilla}/estado', [CounterController::class, 'toggleStatus'])
            ->middleware('permission:ventanillas.gestionar')
            ->name('ventanillas.toggle-status');
        Route::post('/servicios', [ServiceController::class, 'store'])
            ->middleware('permission:servicios.crear')
            ->name('servicios.store');

        // Editar servicios
        Route::get('/servicios/{servicio}/editar', [ServiceController::class, 'edit'])
            ->middleware('permission:servicios.editar')
            ->name('servicios.edit');

        Route::put('/servicios/{servicio}', [ServiceController::class, 'update'])
            ->middleware('permission:servicios.editar')
            ->name('servicios.update');

        // Activar o desactivar servicios
        Route::patch('/servicios/{servicio}/estado', [ServiceController::class, 'toggleStatus'])
            ->middleware('permission:servicios.editar')
            ->name('servicios.toggle-status');

        // Consultar usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])
            ->middleware('permission:usuarios.ver')
            ->name('usuarios.index');

        // Registrar usuarios
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])
            ->middleware('permission:usuarios.crear')
            ->name('usuarios.create');

        Route::post('/usuarios', [UsuarioController::class, 'store'])
            ->middleware('permission:usuarios.crear')
            ->name('usuarios.store');

        // Editar usuarios
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])
            ->middleware('permission:usuarios.editar')
            ->name('usuarios.edit');

        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
            ->middleware('permission:usuarios.editar')
            ->name('usuarios.update');

        // Eliminar usuarios
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])
            ->middleware('permission:usuarios.eliminar')
            ->name('usuarios.destroy');
    });
// ==========================================
// MÓDULO DE TURNOS DEL CIUDADANO
// ==========================================

Route::middleware(['auth', 'verified'])
    ->prefix('ciudadano')
    ->name('ciudadano.')
    ->group(function () {

        // Consultar mis turnos
        Route::get('/turnos', [TicketController::class, 'index'])
            ->middleware('permission:turnos.ver-propios')
            ->name('turnos.index');

        // Formulario para solicitar turno
        Route::get('/turnos/crear', [TicketController::class, 'create'])
            ->middleware('permission:turnos.crear')
            ->name('turnos.create');

        // Registrar turno
        Route::post('/turnos', [TicketController::class, 'store'])
            ->middleware('permission:turnos.crear')
            ->name('turnos.store');

        // Consultar detalle de un turno propio
        Route::get('/turnos/{ticket}', [TicketController::class, 'show'])
            ->middleware('permission:turnos.ver-propios')
            ->name('turnos.show');
    });

// Perfil del usuario autenticado
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Módulo del operador
    Route::middleware('verified')
        ->prefix('operador')
        ->name('operador.')
        ->group(function () {

            Route::get('/turnos', [OperadorTurnoController::class, 'index'])
                ->middleware('permission:turnos.ver')
                ->name('turnos.index');

            Route::post('/turnos/{ticket}/llamar', [
                OperadorTurnoController::class,
                'call'
            ])
                ->middleware('permission:turnos.llamar')
                ->name('turnos.call');
                // Iniciar atención
Route::post('/turnos/{ticket}/iniciar', [
    OperadorTurnoController::class,
    'start'
])
    ->middleware('permission:turnos.iniciar')
    ->name('turnos.start');

// Finalizar atención
Route::post('/turnos/{ticket}/finalizar', [
    OperadorTurnoController::class,
    'complete'
])
    ->middleware('permission:turnos.finalizar')
    ->name('turnos.complete');


    });
});

require __DIR__.'/auth.php';
