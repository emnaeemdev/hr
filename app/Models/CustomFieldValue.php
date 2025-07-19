<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomFieldValue extends Model
{
    protected $fillable = [
        'custom_field_id',
        'model_type',
        'model_id',
        'value'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the custom field that owns this value.
     */
    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    /**
     * Get the owning model (Employee, Branch, Tool, etc.).
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get formatted value based on field type.
     */
    public function getFormattedValueAttribute()
    {
        $fieldType = $this->customField->field_type;
        
        switch ($fieldType) {
            case 'date':
                return $this->value ? \Carbon\Carbon::parse($this->value)->format('Y-m-d') : null;
            case 'datetime':
                return $this->value ? \Carbon\Carbon::parse($this->value)->format('Y-m-d H:i:s') : null;
            case 'boolean':
                return $this->value ? 'Yes' : 'No';
            case 'number':
                return is_numeric($this->value) ? (float) $this->value : $this->value;
            default:
                return $this->value;
        }
    }

    /**
     * Scope for values of a specific field.
     */
    public function scopeForField($query, $fieldId)
    {
        return $query->where('custom_field_id', $fieldId);
    }

    /**
     * Scope for values of a specific model type.
     */
    public function scopeForModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }
}
