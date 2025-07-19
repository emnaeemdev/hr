<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'full_name',
        'position',
        'branch_id',
        'national_id',
        'employee_code',
        'phone',
        'address',
        'hire_date',
        'work_hours',
        'monthly_salary',
        'salary',
        'has_advance',
        'documents_complete',
        'tools_received',
        'notes',
        'profile_image',
        'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'monthly_salary' => 'decimal:2',
        'has_advance' => 'boolean',
        'documents_complete' => 'boolean',
        'tools_received' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the branch that owns the employee.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the advances for the employee.
     */
    public function advances(): HasMany
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    /**
     * Get the documents for the employee.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * Get the tools assigned to the employee.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'employee_tool')
                    ->withPivot(['quantity_assigned', 'assigned_date', 'return_date', 'return_status', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get the tools currently assigned to the employee.
     */
    public function assignedTools(): HasMany
    {
        return $this->hasMany(Tool::class, 'assigned_employee_id')->where('status', 'assigned');
    }

    /**
     * Get all custom field values for this employee.
     */
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    /**
     * Get active advances for the employee.
     */
    public function activeAdvances(): HasMany
    {
        return $this->advances()->whereIn('status', ['approved', 'paid'])->where('remaining_amount', '>', 0);
    }

    /**
     * Get total remaining advances amount.
     */
    public function getTotalRemainingAdvancesAttribute(): float
    {
        return $this->advances()->whereIn('status', ['approved', 'paid'])->sum('remaining_amount');
    }

    /**
     * Get total active advances amount.
     */
    public function getTotalActiveAdvancesAttribute(): float
    {
        return $this->getTotalRemainingAdvancesAttribute();
    }

    /**
     * Get net salary after deducting all advances.
     */
    public function getNetSalaryAttribute(): float
    {
        $totalAdvances = $this->getTotalRemainingAdvancesAttribute();
        $netSalary = ($this->monthly_salary ?? 0) - $totalAdvances;
        return max(0, $netSalary); // Ensure salary doesn't go negative
    }

    /**
     * Get remaining salary after deducting advances.
     */
    public function getRemainingSalaryAttribute(): float
    {
        return $this->getNetSalaryAttribute();
    }

    /**
     * Check if employee has unreturned tools.
     */
    public function hasUnreturnedTools(): bool
    {
        return $this->tools()->wherePivot('return_status', 'assigned')->exists();
    }

    /**
     * Get unverified documents count.
     */
    public function getUnverifiedDocumentsCountAttribute(): int
    {
        return $this->documents()->where('is_verified', false)->count();
    }

    /**
     * Scope for active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for employees with incomplete documents.
     */
    public function scopeIncompleteDocuments($query)
    {
        return $query->where('documents_complete', false);
    }

    /**
     * Scope for employees by branch.
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
