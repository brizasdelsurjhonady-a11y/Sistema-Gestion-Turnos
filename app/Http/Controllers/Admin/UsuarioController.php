<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Mostrar usuarios registrados.
     */
    public function index(Request $request)
    {
        $usuarios = User::with('roles')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                      ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de registro.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name');

        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Registrar un usuario.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ]);

        DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password'],
            ]);

            $usuario->syncRoles([$datos['role']]);
        });

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(User $usuario)
    {
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name');

        $rolActual = $usuario->getRoleNames()->first();

        return view(
            'admin.usuarios.edit',
            compact('usuario', 'roles', 'rolActual')
        );
    }

    /**
     * Actualizar usuario.
     */
    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'password' => ['nullable', 'string', 'min:12', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ]);

        $nuevoRol = $datos['role'];

        // Evitar que el administrador se quite su propio rol.
        if (
            $usuario->is(auth()->user()) &&
            $usuario->hasRole('Administrador') &&
            $nuevoRol !== 'Administrador'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'No puedes quitarte tu propio rol de administrador.',
                ]);
        }

        // Proteger al último administrador del sistema.
        if (
            $usuario->hasRole('Administrador') &&
            $nuevoRol !== 'Administrador' &&
            User::role('Administrador')->count() <= 1
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'No puedes quitar el rol al último administrador.',
                ]);
        }

        DB::transaction(function () use ($datos, $usuario) {
            $usuario->name = $datos['name'];
            $usuario->email = $datos['email'];

            if (!empty($datos['password'])) {
                $usuario->password = $datos['password'];
            }

            $usuario->save();
            $usuario->syncRoles([$datos['role']]);
        });

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario.
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminar la cuenta actual.
        if ($usuario->is(auth()->user())) {
            return back()->with(
                'error',
                'No puedes eliminar tu propia cuenta.'
            );
        }

        // No permitir eliminar al último administrador.
        if (
            $usuario->hasRole('Administrador') &&
            User::role('Administrador')->count() <= 1
        ) {
            return back()->with(
                'error',
                'No puedes eliminar al último administrador del sistema.'
            );
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
