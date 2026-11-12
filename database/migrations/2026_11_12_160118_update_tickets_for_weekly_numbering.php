
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->date('week_start')
                ->nullable()
                ->after('ticket_date');
        });

        // Calcular el lunes correspondiente a cada turno existente.
        DB::statement("
            UPDATE tickets
            SET week_start = DATE_SUB(
                ticket_date,
                INTERVAL WEEKDAY(ticket_date) DAY
            )
        ");

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique('tickets_ticket_number_unique');
        });

        DB::statement("
            ALTER TABLE tickets
            MODIFY week_start DATE NOT NULL
        ");

        Schema::table('tickets', function (Blueprint $table) {
            $table->unique(
                ['service_id', 'week_start', 'ticket_number'],
                'tickets_service_week_number_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique('tickets_service_week_number_unique');
        });

        // El índice global solo puede restaurarse si no hay
        // números repetidos entre semanas o servicios.
        Schema::table('tickets', function (Blueprint $table) {
            $table->unique('ticket_number');
            $table->dropColumn('week_start');
        });
    }
};
