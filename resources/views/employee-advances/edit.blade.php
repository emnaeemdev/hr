@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تعديل السلفة</h4>
                    <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee-advances.update', $employeeAdvance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">الموظف <span class="text-danger">*</span></label>
                                    <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                        <option value="">اختر الموظف</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', $employeeAdvance->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }} - {{ $employee->branch->name ?? 'غير محدد' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">مبلغ السلفة <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount', $employeeAdvance->amount) }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="remaining_amount" class="form-label">المبلغ المتبقي</label>
                                    <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" 
                                           id="remaining_amount" name="remaining_amount" value="{{ old('remaining_amount', $employeeAdvance->remaining_amount) }}">
                                    @error('remaining_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $employeeAdvance->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="approved" {{ old('status', $employeeAdvance->status) == 'approved' ? 'selected' : '' }}>موافق عليها</option>
                                        <option value="paid" {{ old('status', $employeeAdvance->status) == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                        <option value="rejected" {{ old('status', $employeeAdvance->status) == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_date" class="form-label">تاريخ الطلب</label>
                                    <input type="date" class="form-control @error('request_date') is-invalid @enderror" 
                                           id="request_date" name="request_date" value="{{ old('request_date', $employeeAdvance->request_date ? \Carbon\Carbon::parse($employeeAdvance->request_date)->format('Y-m-d') : '') }}">
                                    @error('request_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="advance_date" class="form-label">تاريخ السلفة</label>
                                    <input type="date" class="form-control @error('advance_date') is-invalid @enderror" 
                                           id="advance_date" name="advance_date" value="{{ old('advance_date', $employeeAdvance->advance_date ? \Carbon\Carbon::parse($employeeAdvance->advance_date)->format('Y-m-d') : '') }}">
                                    @error('advance_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">الملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $employeeAdvance->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($customFields->count() > 0)
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">الحقول المخصصة</h5>
                                </div>
                                @foreach($customFields as $field)
                                    @php
                                        $fieldValue = $employeeAdvance->customFieldValues->where('custom_field_id', $field->id)->first();
                                        $currentValue = $fieldValue ? $fieldValue->value : '';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="custom_field_{{ $field->id }}" class="form-label">
                                                {{ $field->label }}
                                                @if($field->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            @if($field->field_type == 'text')
                                                <input type="text" class="form-control" 
                                                       id="custom_field_{{ $field->id }}" 
                                                       name="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_field_' . $field->id, $currentValue) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'number')
                                                <input type="number" class="form-control" 
                                                       id="custom_field_{{ $field->id }}" 
                                                       name="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_field_' . $field->id, $currentValue) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'date')
                                                <input type="date" class="form-control" 
                                                       id="custom_field_{{ $field->id }}" 
                                                       name="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_field_' . $field->id, $currentValue) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'textarea')
                                                <textarea class="form-control" 
                                                          id="custom_field_{{ $field->id }}" 
                                                          name="custom_field_{{ $field->id }}"
                                                          rows="3"
                                                          {{ $field->is_required ? 'required' : '' }}>{{ old('custom_field_' . $field->id, $currentValue) }}</textarea>
                                            @elseif($field->field_type == 'select')
                                                <select class="form-select" 
                                                        id="custom_field_{{ $field->id }}" 
                                                        name="custom_field_{{ $field->id }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                    <option value="">اختر...</option>
                                                    @if($field->options)
                                                        @foreach($field->options as $option)
                                                            <option value="{{ $option }}" 
                                                                {{ old('custom_field_' . $field->id, $currentValue) == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @elseif($field->field_type == 'checkbox')
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           id="custom_field_{{ $field->id }}" 
                                                           name="custom_field_{{ $field->id }}"
                                                           value="1"
                                                           {{ old('custom_field_' . $field->id, $currentValue) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="custom_field_{{ $field->id }}">
                                                        {{ $field->label }}
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ التعديلات
                                    </button>
                                    <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> إلغاء
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#employee_id').select2({
        placeholder: 'اختر الموظف',
        allowClear: true,
        language: {
            noResults: function() {
                return 'لا توجد نتائج';
            },
            searching: function() {
                return 'جاري البحث...';
            }
        }
    });
});
</script>
@endsection