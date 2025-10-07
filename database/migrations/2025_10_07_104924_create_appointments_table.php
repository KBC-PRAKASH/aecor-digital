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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->index('appointment_user_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('healthcare_professional_id')
                ->constrained('healthcare_professionals', 'id')
                ->index('appointment_healthcare_professional_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->dateTime('appointment_start_time');
            $table->dateTime('appointment_end_time');
            $table->enum('status', ['booked', 'completed', 'cancelled'])->index()->default('booked');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
