<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TurnoController extends Controller
{
    /**
     * Mostrar turnos pendientes y turnos asignados al operador.
     */
    public function index()
    {
        $counters = Counter::with('service')
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        $serviceIds = $counters->pluck('service_id')->unique();

        $tickets = Ticket::with(['user', 'service', 'counter'])
            ->whereIn('service_id', $serviceIds)
            ->where(function ($query) {
                $query->where('status', 'waiting')
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereIn('status', [
                            'called',
                            'in_progress',
                        ])->whereHas('counter', function ($counterQuery) {
                            $counterQuery->where(
                                'user_id',
                                auth()->id()
                            );
                        });
                    });
            })
            ->orderBy('created_at')
            ->paginate(15);

        return view('operador.turnos.index', compact(
            'counters',
            'tickets'
        ));
    }

    /**
     * Registrar una acción sobre un turno.
     */
    private function changeStatus(
        Ticket $ticket,
        string $expectedStatus,
        string $newStatus,
        string $action,
        string $description
    ) {
        try {
            DB::transaction(function () use (
                $ticket,
                $expectedStatus,
                $newStatus,
                $action,
                $description
            ) {
                $lockedTicket = Ticket::whereKey($ticket->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedTicket->status !== $expectedStatus) {
                    throw new RuntimeException(
                        'El turno ya no tiene el estado esperado.'
                    );
                }

                $counter = Counter::whereKey($lockedTicket->counter_id)
                    ->where('user_id', auth()->id())
                    ->where('service_id', $lockedTicket->service_id)
                    ->where('is_active', true)
                    ->first();

                if (!$counter) {
                    throw new RuntimeException(
                        'El turno no está asignado a una ventanilla activa tuya.'
                    );
                }

                $lockedTicket->status = $newStatus;

                if ($newStatus === 'in_progress') {
                    $lockedTicket->started_at = now();
                }

                if ($newStatus === 'completed') {
                    $lockedTicket->completed_at = now();
                }

                $lockedTicket->save();

                TicketLog::create([
                    'ticket_id' => $lockedTicket->id,
                    'user_id' => auth()->id(),
                    'action' => $action,
                    'previous_status' => $expectedStatus,
                    'new_status' => $newStatus,
                    'description' => $description . ' - ' . $counter->code,
                ]);
            });

            return redirect()
                ->route('operador.turnos.index')
                ->with('success', 'Estado del turno actualizado correctamente.');

        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'ticket' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Llamar al ciudadano y asignarle una ventanilla.
     */
    public function call(Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($ticket) {
                $lockedTicket = Ticket::whereKey($ticket->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedTicket->status !== 'waiting') {
                    throw new RuntimeException(
                        'Este turno ya no está en espera.'
                    );
                }

                $counter = Counter::where('user_id', auth()->id())
                    ->where('service_id', $lockedTicket->service_id)
                    ->where('is_active', true)
                    ->first();

                if (!$counter) {
                    throw new RuntimeException(
                        'No tienes una ventanilla activa para este servicio.'
                    );
                }

                $lockedTicket->counter_id = $counter->id;
                $lockedTicket->status = 'called';
                $lockedTicket->called_at = now();
                $lockedTicket->save();

                TicketLog::create([
                    'ticket_id' => $lockedTicket->id,
                    'user_id' => auth()->id(),
                    'action' => 'called',
                    'previous_status' => 'waiting',
                    'new_status' => 'called',
                    'description' => 'Turno llamado desde la ventanilla '
                        . $counter->code,
                ]);
            });

            return redirect()
                ->route('operador.turnos.index')
                ->with('success', 'Turno llamado correctamente.');

        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'ticket' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Iniciar la atención.
     */
    public function start(Ticket $ticket)
    {
        return $this->changeStatus(
            $ticket,
            'called',
            'in_progress',
            'started',
            'Atención iniciada'
        );
    }

    /**
     * Finalizar la atención.
     */
    public function complete(Ticket $ticket)
    {
        return $this->changeStatus(
            $ticket,
            'in_progress',
            'completed',
            'completed',
            'Atención finalizada'
        );
    }
}
