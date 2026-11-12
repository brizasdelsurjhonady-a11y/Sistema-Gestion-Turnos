<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TicketCreationService
{
    public function createForCitizen(
        int $userId,
        int $serviceId,
        ?string $observations = null
    ): Ticket {
        return DB::transaction(function () use (
            $userId,
            $serviceId,
            $observations
        ) {
            // Bloquear el servicio para evitar números duplicados.
            $service = Service::whereKey($serviceId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$service->is_active) {
                throw new RuntimeException(
                    'El servicio seleccionado no está activo.'
                );
            }

            // Obtener la fecha actual y el lunes de esta semana.
            $now = now();

            $ticketDate = $now->toDateString();

            $weekStart = $now->copy()
                ->startOfWeek(Carbon::MONDAY)
                ->toDateString();

            // Buscar el último turno de este servicio y semana.
            $lastTicketNumber = Ticket::where('service_id', $service->id)
                ->where('week_start', $weekStart)
                ->orderByDesc('id')
                ->value('ticket_number');

            // Calcular el siguiente número.
            $nextNumber = 1;

            if ($lastTicketNumber !== null) {
                if (preg_match('/(\d+)$/', $lastTicketNumber, $matches)) {
                    $nextNumber = ((int) $matches[1]) + 1;
                } else {
                    throw new RuntimeException(
                        'No se pudo calcular el número del último turno.'
                    );
                }
            }

            $ticketNumber = $service->code . '-'
                . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

            // Registrar el turno.
            $ticket = Ticket::create([
                'user_id' => $userId,
                'service_id' => $service->id,
                'ticket_number' => $ticketNumber,
                'ticket_date' => $ticketDate,
                'week_start' => $weekStart,
                'status' => 'waiting',
                'observations' => $observations,
            ]);

            return $ticket->load(['user', 'service']);
        });
    }
}
