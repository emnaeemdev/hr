<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEntitlement extends Model
{
    protected $fillable = [
        'employee_id',
        'monthly_hours',
        'monthly_days',
        'hourly_rate',
        'days_worked',
        'daily_hours',
        'actual_hours',
        'entitlements_by_hours',
        'full_salary',
        'daily_salary',
        'entitlements_by_salary',
        'net_salary_by_hours',
        'net_salary_by_salary',
        'total_advances',
        'notes'
    ];

    protected $casts = [
        'monthly_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'daily_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'entitlements_by_hours' => 'decimal:2',
        'full_salary' => 'decimal:2',
        'daily_salary' => 'decimal:2',
        'entitlements_by_salary' => 'decimal:2',
        'net_salary_by_hours' => 'decimal:2',
        'net_salary_by_salary' => 'decimal:2',
        'total_advances' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
