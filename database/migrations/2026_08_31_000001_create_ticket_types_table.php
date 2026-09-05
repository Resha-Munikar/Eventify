<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ticket_types')) {
            Schema::create('ticket_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('quantity');
                $table->integer('sold_quantity')->default(0);
                $table->dateTime('sale_start')->nullable();
                $table->dateTime('sale_end')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // Safe Backfill: Ensure existing events have at least one default ticket type
        if (Schema::hasTable('events') && Schema::hasTable('ticket_types')) {
            $events = DB::table('events')->get();
            foreach ($events as $event) {
                $existingTicket = DB::table('ticket_types')->where('event_id', $event->id)->first();
                if (!$existingTicket) {
                    DB::table('ticket_types')->insert([
                        'event_id' => $event->id,
                        'name' => 'General Admission',
                        'description' => 'Standard event admission ticket',
                        'price' => $event->price ?? 0,
                        'quantity' => $event->available_seats ?? 50,
                        'sold_quantity' => 0,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
