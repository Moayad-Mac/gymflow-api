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
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('restrict');
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade');
            $table->integer('duration');
            $table->tinyInteger('day_of_week');
            $table->time('starts_at');
            $table->integer('capacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_classes');
    }
};
