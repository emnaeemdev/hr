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
        Schema::create('employee_tool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->integer('quantity_assigned');
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->enum('return_status', ['assigned', 'returned', 'lost'])->default('assigned');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['employee_id', 'tool_id']);
            $table->index(['employee_id', 'return_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_tool');
    }
};
