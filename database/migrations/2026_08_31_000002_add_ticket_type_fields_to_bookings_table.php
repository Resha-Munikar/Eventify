<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'ticket_type_id')) {
                $table->foreignId('ticket_type_id')->nullable()->after('event_id')->constrained('ticket_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'price_per_ticket')) {
                $table->decimal('price_per_ticket', 10, 2)->nullable()->after('tickets');
            }
            if (!Schema::hasColumn('bookings', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable()->after('price_per_ticket');
            }
            if (!Schema::hasColumn('bookings', 'service_charge')) {
                $table->decimal('service_charge', 10, 2)->default(0.00)->after('subtotal');
            }
            if (!Schema::hasColumn('bookings', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('service_charge');
            }
            if (!Schema::hasColumn('bookings', 'booking_status')) {
                $table->string('booking_status')->default('confirmed')->after('total_amount');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('paid')->after('booking_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'ticket_type_id')) {
                $table->dropForeign(['ticket_type_id']);
                $table->dropColumn('ticket_type_id');
            }
            $columnsToDrop = [];
            foreach (['price_per_ticket', 'subtotal', 'service_charge', 'total_amount', 'booking_status', 'payment_status'] as $col) {
                if (Schema::hasColumn('bookings', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
