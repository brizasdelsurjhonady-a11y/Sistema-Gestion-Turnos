<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            $table->foreignId('counter_id')
                ->nullable()
                ->constrained('counters')
                ->nullOnDelete();

            $table->string('ticket_number', 30)->unique();

            $table->date('ticket_date');

            $table->enum('status', [
                'waiting',
                'called',
                'in_progress',
                'completed',
                'no_show',
                'cancelled',
            ])->default('waiting');

            $table->timestamp('called_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();

            $table->index(['service_id', 'ticket_date', 'status']);
            $table->index(['counter_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
