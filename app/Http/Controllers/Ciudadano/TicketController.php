<?php

namespace App\Http\Controllers\Ciudadano;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Ticket;
use App\Services\TicketCreationService;
use Illuminate\Http\Request;
use RuntimeException;

class TicketController extends Controller
{
    /**
     * Mostrar los turnos del ciudadano autenticado.
     */
    public function index()
    {
        $tickets = Ticket::with('service')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('ciudadano.turnos.index', compact('tickets'));
    }

    /**
     * Mostrar formulario para solicitar un turno.
     */
    public function create()
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('ciudadano.turnos.create', compact('services'));
    }

    /**
     * Registrar un nuevo turno.
     */
    public function store(
        Request $request,
        TicketCreationService $ticketCreationService
    ) {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'observations' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $ticket = $ticketCreationService->createForCitizen(
                auth()->id(),
                (int) $validated['service_id'],
                $validated['observations'] ?? null
            );

            return redirect()
                ->route('ciudadano.turnos.show', $ticket)
                ->with('success', 'Tu turno fue registrado correctamente.');
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['service_id' => $exception->getMessage()]);
        }
    }

    /**
     * Mostrar el detalle de un turno propio.
     */
    public function show(Ticket $ticket)
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $ticket->load('service', 'counter');

        return view('ciudadano.turnos.show', compact('ticket'));
    }
}
