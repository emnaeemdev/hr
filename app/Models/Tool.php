<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tool extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'quantity',
        'description',
        'purchase_date',
        'purchase_price',
        'status',
        'assigned_employee_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the assigned employee for this tool.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }

    /**
     * Get the employees that have this tool.
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_tool')
                    ->withPivot(['quantity_assigned', 'assigned_date', 'return_date', 'return_status', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get all custom field values for this tool.
     */
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    /**
     * Get available quantity (total - assigned).
     */
    public function getAvailableQuantityAttribute(): int
    {
        $assignedQuantity = $this->employees()
            ->wherePivot('return_status', 'assigned')
            ->sum('employee_tool.quantity_assigned');
        
        return $this->quantity - $assignedQuantity;
    }
}
