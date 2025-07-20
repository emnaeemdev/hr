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
        Schema::table('employee_entitlements', function (Blueprint $table) {
            $table->decimal('net_salary_by_hours', 10, 2)->nullable()->after('entitlements_by_salary');
            $table->decimal('net_salary_by_salary', 10, 2)->nullable()->after('net_salary_by_hours');
            $table->decimal('total_advances', 10, 2)->default(0)->after('net_salary_by_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_entitlements', function (Blueprint $table) {
            $table->dropColumn(['net_salary_by_hours', 'net_salary_by_salary', 'total_advances']);
        });
    }
};
