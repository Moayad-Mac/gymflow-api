<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('gym_class_id')->constrained('gym_classes')->onDelete('cascade');
            $table->enum('status', ['confirmed', 'cancelled', 'completed', 'no_show']);
            $table->timestamp('booked_at');
            $table->date('class_date');
            $table->unique(['member_id', 'gym_class_id', 'class_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
