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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('national_id')->unique()->nullable();
            $table->string('employee_code')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable();
            $table->integer('work_hours')->default(8);
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->boolean('has_advance')->default(false);
            $table->boolean('documents_complete')->default(false);
            $table->boolean('tools_received')->default(false);
            $table->text('notes')->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('status', ['active', 'resigned', 'terminated'])->default('active');
            $table->timestamps();
            
            $table->index(['branch_id', 'status']);
            $table->index('national_id');
            $table->index('employee_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
