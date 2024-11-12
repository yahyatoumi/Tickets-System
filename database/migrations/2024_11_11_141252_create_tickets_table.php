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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description');
            $table->enum('status', ['in_progress', 'pending', 'resolved'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->foreignId('submitter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_tech_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
