<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EmployeeAdvance extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'remaining_amount',
        'request_date',
        'advance_date',
        'reason',
        'notes',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'request_date' => 'date',
        'advance_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'remaining_amount' => 0,
    ];

    /**
     * Get the employee that owns the advance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the custom field values for this advance.
     */
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    /**
     * Get paid amount.
     */
    public function getPaidAmountAttribute(): float
    {
        return $this->amount - $this->remaining_amount;
    }

    /**
     * Check if advance is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Scope for active advances.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed advances.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
