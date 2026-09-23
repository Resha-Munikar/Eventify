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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('type')->default('general'); // general, vendor, event, venue
            $table->foreignId('vendor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained('venues')->nullOnDelete();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read, resolved
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Indexes for fast searching and filtering
            $table->index('type');
            $table->index('status');
            $table->index('vendor_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
