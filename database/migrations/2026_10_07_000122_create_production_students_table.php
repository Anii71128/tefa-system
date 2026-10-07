<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production_students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('task');

            $table->date('deadline')->nullable();

            $table->enum('status', [
                'assigned',
                'in_progress',
                'completed',
            ])->default('assigned');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_students');
    }
};
