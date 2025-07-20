@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إضافة موظف جديد</h4>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST" id="employee-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الموظف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                           id="phone" name="phone" value="{{ old('phone') }}">
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
                                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                    <label for="position" class="form-label">الوظيفة</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                           id="position" name="position" value="{{ old('position') }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">تاريخ التوظيف</label>
                                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" name="hire_date" value="{{ old('hire_date') }}">
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        

                        
                        <!-- Custom Fields -->
                        @if($customFields && $customFields->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    
                                </div>
                                @foreach($customFields as $field)
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
                                                       value="{{ old('custom_field_' . $field->id) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'number')
                                                <input type="number" class="form-control" 
                                                       id="custom_field_{{ $field->id }}" 
                                                       name="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_field_' . $field->id) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'date')
                                                <input type="date" class="form-control" 
                                                       id="custom_field_{{ $field->id }}" 
                                                       name="custom_field_{{ $field->id }}"
                                                       value="{{ old('custom_field_' . $field->id) }}"
                                                       {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->field_type == 'textarea')
                                                <textarea class="form-control" 
                                                          id="custom_field_{{ $field->id }}" 
                                                          name="custom_field_{{ $field->id }}"
                                                          rows="3"
                                                          {{ $field->is_required ? 'required' : '' }}>{{ old('custom_field_' . $field->id) }}</textarea>
                                            @elseif($field->field_type == 'select')
                                                <select class="form-select" 
                                                        id="custom_field_{{ $field->id }}" 
                                                        name="custom_field_{{ $field->id }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                    <option value="">اختر...</option>
                                                    @if($field->options)
                                                        @foreach($field->options as $option)
                                                            <option value="{{ $option }}" 
                                                                {{ old('custom_field_' . $field->id) == $option ? 'selected' : '' }}>
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
                                                           {{ old('custom_field_' . $field->id) ? 'checked' : '' }}>
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
                        
                        <!-- Entitlements Calculator -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">حاسبة المستحقات</h5>
                                    </div>
                                    <div class="card-body" id="entitlements-section">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="monthly_hours" class="form-label">عدد الساعات الشهرية</label>
                                                    <input type="number" class="form-control" id="monthly_hours" value="208" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="hourly_rate" class="form-label">سعر الساعة (جنيه)</label>
                                                    <input type="number" class="form-control" id="hourly_rate" value="36.06" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="days_worked" class="form-label">عدد الأيام المعمولة</label>
                                                    <input type="number" class="form-control" id="days_worked" value="26" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="monthly_days" class="form-label">عدد الأيام الشهرية</label>
                                                    <input type="number" class="form-control" id="monthly_days" value="26" step="0.01">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title">الحساب بالساعات</h6>
                                                        <p class="mb-0">إجمالي الراتب: <span id="total_by_hours" class="fw-bold">7,500.48 جنيه</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title">الحساب بالراتب الكامل</h6>
                                                        <p class="mb-0">إجمالي الراتب: <span id="total_by_salary" class="fw-bold">7,500.48 جنيه</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to initialize event listeners with retry mechanism
    function initializeEventListeners(retryCount = 0) {
        try {
            // Get input elements
            const monthlyHoursInput = document.getElementById('monthly_hours');
            const hourlyRateInput = document.getElementById('hourly_rate');
            const daysWorkedInput = document.getElementById('days_worked');
            const monthlyDaysInput = document.getElementById('monthly_days');

            const employeeForm = document.getElementById('employee-form');
            
            // Check if all required elements exist
            if (!monthlyHoursInput || !hourlyRateInput || !daysWorkedInput || !monthlyDaysInput) {
                if (retryCount < 5) {
                    setTimeout(() => initializeEventListeners(retryCount + 1), 500);
                    return;
                }
                console.warn('Some entitlement calculator elements not found');
                return;
            }
            
            // Function to calculate entitlements
            function calculateEntitlements() {
                const monthlyHours = parseFloat(monthlyHoursInput.value) || 0;
                const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                const daysWorked = parseFloat(daysWorkedInput.value) || 0;
                const monthlyDays = parseFloat(monthlyDaysInput.value) || 0;
                
                // Calculate by hours
                const totalByHours = (daysWorked / monthlyDays) * monthlyHours * hourlyRate;
                
                // Calculate by full salary
                const totalBySalary = monthlyHours * hourlyRate;
                
                // Update display - by hours
                const totalByHoursEl = document.getElementById('total_by_hours');
                
                if (totalByHoursEl) totalByHoursEl.textContent = Math.round(totalByHours) + ' جنيه';
                
                // Update display - by salary
                const totalBySalaryEl = document.getElementById('total_by_salary');
                
                if (totalBySalaryEl) totalBySalaryEl.textContent = Math.round(totalBySalary) + ' جنيه';
            }
            
            // Add event listeners to all inputs
            monthlyHoursInput.addEventListener('input', calculateEntitlements);
            hourlyRateInput.addEventListener('input', calculateEntitlements);
            daysWorkedInput.addEventListener('input', calculateEntitlements);
            monthlyDaysInput.addEventListener('input', calculateEntitlements);
            
            // Initial calculation
            calculateEntitlements();
            

            
            // Add entitlements data to form before submission
            if (employeeForm) {
                employeeForm.addEventListener('submit', function(e) {
                    // Add hidden inputs for entitlements data
                    const entitlementsData = [
                        { name: 'monthly_hours', value: monthlyHoursInput.value },
                        { name: 'hourly_rate', value: hourlyRateInput.value },
                        { name: 'days_worked', value: daysWorkedInput.value },
                        { name: 'monthly_days', value: monthlyDaysInput.value },

                    ];
                    
                    entitlementsData.forEach(function(data) {
                        if (data.value) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = data.name;
                            input.value = data.value;
                            e.target.appendChild(input);
                        }
                    });
                });
            }
        } catch (error) {
            console.error('Error initializing entitlements calculator:', error);
            if (retryCount < 5) {
                setTimeout(() => initializeEventListeners(retryCount + 1), 1000);
            }
        }
    }
    
    // Initialize event listeners
    initializeEventListeners();
});
</script>