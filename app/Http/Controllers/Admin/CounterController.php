<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CounterController extends Controller
{
    public function index(Request $request)
    {
        $ventanillas = Counter::with(['service', 'user'])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('code', 'like', "%{$buscar}%")
                        ->orWhere('name', 'like', "%{$buscar}%")
                        ->orWhereHas('service', function ($service) use ($buscar) {
                            $service->where('name', 'like', "%{$buscar}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.ventanillas.index', compact('ventanillas'));
    }

    public function create()
    {
        $servicios = Service::orderBy('name')->get();
        $operadores = User::role('Operador')->orderBy('name')->get();

        return view('admin.ventanillas.create', compact(
            'servicios',
            'operadores'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'user_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value && !User::role('Operador')->whereKey($value)->exists()) {
                        $fail('El usuario seleccionado no tiene el rol Operador.');
                    }
                },
            ],
            'code' => ['required', 'string', 'max:20', 'unique:counters,code'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        Counter::create($datos);

        return redirect()
            ->route('admin.ventanillas.index')
            ->with('success', 'Ventanilla registrada correctamente.');
    }

    public function edit(Counter $ventanilla)
    {
        $servicios = Service::orderBy('name')->get();
        $operadores = User::role('Operador')->orderBy('name')->get();

        return view('admin.ventanillas.edit', compact(
            'ventanilla',
            'servicios',
            'operadores'
        ));
    }

    public function update(Request $request, Counter $ventanilla)
    {
        $datos = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'user_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value && !User::role('Operador')->whereKey($value)->exists()) {
                        $fail('El usuario seleccionado no tiene el rol Operador.');
                    }
                },
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('counters', 'code')->ignore($ventanilla->id),
            ],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $ventanilla->update($datos);

        return redirect()
            ->route('admin.ventanillas.index')
            ->with('success', 'Ventanilla actualizada correctamente.');
    }

    public function toggleStatus(Counter $ventanilla)
    {
        $ventanilla->update([
            'is_active' => !$ventanilla->is_active,
        ]);

        return redirect()
            ->route('admin.ventanillas.index')
            ->with('success', 'Estado de la ventanilla actualizado correctamente.');
    }
}
