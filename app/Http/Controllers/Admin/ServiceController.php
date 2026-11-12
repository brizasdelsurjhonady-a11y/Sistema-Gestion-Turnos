<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Mostrar servicios registrados.
     */
    public function index(Request $request)
    {
        $servicios = Service::query()
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                      ->orWhere('code', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Mostrar formulario de registro.
     */
    public function create()
    {
        return view('admin.servicios.create');
    }

    /**
     * Registrar servicio.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:services,code'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);

        Service::create($datos);

        return redirect()
            ->route('admin.servicios.index')
            ->with('success', 'Servicio registrado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Service $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    /**
     * Actualizar servicio.
     */
    public function update(Request $request, Service $servicio)
    {
        $datos = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('services', 'code')->ignore($servicio->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);

        $servicio->update($datos);

        return redirect()
            ->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Activar o desactivar servicio.
     */
    public function toggleStatus(Service $servicio)
    {
        $servicio->update([
            'is_active' => !$servicio->is_active,
        ]);

        return redirect()
            ->route('admin.servicios.index')
            ->with('success', 'Estado del servicio actualizado correctamente.');
    }
}
