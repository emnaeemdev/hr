@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تعديل بيانات الموظف: {{ $employee->name }}</h4>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الموظف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">الفرع <span class="text-danger">*</span></label>
                                    <select class="form-select @error('branch_id') is-invalid @enderror" 
                                            id="branch_id" name="branch_id" required>
                                        <option value="">اختر الفرع</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" 
                                                {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">المنصب</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                           id="position" name="position" value="{{ old('position', $employee->position) }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">تاريخ التوظيف</label>
                                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" name="hire_date" 
                                           value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}">
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_hours" class="form-label">ساعات العمل الإجمالية <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('work_hours') is-invalid @enderror" 
                                           id="work_hours" name="work_hours" value="{{ old('work_hours', $employee->work_hours ?? 8) }}" 
                                           min="1" step="0.5" required>
                                    @error('work_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly_salary" class="form-label">الراتب الشهري <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('monthly_salary') is-invalid @enderror" 
                                           id="monthly_salary" name="monthly_salary" value="{{ old('monthly_salary', $employee->monthly_salary) }}" 
                                           step="0.01" min="0" required>
                                    <div class="form-text">بالجنيه المصري</div>
                                    @error('monthly_salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Fields -->
                        @if($customFields && $customFields->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">الحقول المخصصة</h5>
                                </div>
                                @foreach($customFields as $field)
                                    @php
                                        $existingValue = $employee->customFieldValues->where('custom_field_id', $field->id)->first();
                                        $currentValue = $existingValue ? $existingValue->value : '';
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
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection