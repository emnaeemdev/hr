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
        Schema::create('employee_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('monthly_hours', 8, 2)->default(208); // ساعات الشهر الكاملة
            $table->integer('monthly_days')->default(26); // أيام العمل الشهرية
            $table->decimal('hourly_rate', 8, 2)->default(36.06); // المعدل بالساعة
            $table->integer('days_worked')->default(26); // أيام العمل الفعلية
            $table->decimal('daily_hours', 8, 2); // ساعات اليوم الواحد
            $table->decimal('actual_hours', 8, 2); // إجمالي الساعات الفعلية
            $table->decimal('entitlements_by_hours', 10, 2); // المستحقات بالساعات
            $table->decimal('full_salary', 10, 2); // الراتب الكامل
            $table->decimal('daily_salary', 10, 2); // قيمة اليوم الواحد
            $table->decimal('entitlements_by_salary', 10, 2); // المستحقات بالراتب
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_entitlements');
    }
};
