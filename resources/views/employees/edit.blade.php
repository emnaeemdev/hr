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
                                    <label for="position" class="form-label">الوظيفة</label>
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
                        
                        <!-- Entitlements Calculator -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">حاسبة المستحقات</h5>
                                    </div>
                                    <div class="card-body" id="entitlements-section">
                                        @php
                                            // Get latest saved entitlement values or use defaults
                                            $latestEntitlement = $employee->latestEntitlement;
                                            $defaultMonthlyHours = $latestEntitlement ? $latestEntitlement->monthly_hours : 208;
                                            $defaultHourlyRate = $latestEntitlement ? $latestEntitlement->hourly_rate : 36.06;
                                            $defaultDaysWorked = $latestEntitlement ? $latestEntitlement->days_worked : 26;
                                            $defaultMonthlyDays = $latestEntitlement ? $latestEntitlement->monthly_days : 26;
                                            $savedNotes = $latestEntitlement ? $latestEntitlement->notes : '';
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="monthly_hours" class="form-label">عدد الساعات الشهرية</label>
                                                    <input type="number" class="form-control" id="monthly_hours" value="{{ $defaultMonthlyHours }}" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="hourly_rate" class="form-label">سعر الساعة (جنيه)</label>
                                                    <input type="number" class="form-control" id="hourly_rate" value="{{ $defaultHourlyRate }}" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="days_worked" class="form-label">عدد الأيام المعمولة</label>
                                                    <input type="number" class="form-control" id="days_worked" value="{{ $defaultDaysWorked }}" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="monthly_days" class="form-label">عدد الأيام الشهرية</label>
                                                    <input type="number" class="form-control" id="monthly_days" value="{{ $defaultMonthlyDays }}" step="0.01">
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
                                        
                                        <!-- Save Entitlements Section -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="card border-primary">
                                                    <div class="card-body">
                                                        <h6 class="card-title">حفظ نتائج المستحقات</h6>
                                                        <form id="save-entitlements-form">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-save me-2"></i>حفظ النتائج
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get input elements
    const monthlyHoursInput = document.getElementById('monthly_hours');
    const hourlyRateInput = document.getElementById('hourly_rate');
    const daysWorkedInput = document.getElementById('days_worked');
    const monthlyDaysInput = document.getElementById('monthly_days');
    
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
        document.getElementById('total_by_hours').textContent = Math.ceil(totalByHours) + ' جنيه';
        
        // Update display - by salary
        document.getElementById('total_by_salary').textContent = Math.ceil(totalBySalary) + ' جنيه';
    }
    
    // Add event listeners to all inputs
    monthlyHoursInput.addEventListener('input', function() {
        calculateEntitlements();
        autoSaveEntitlements();
    });
    hourlyRateInput.addEventListener('input', function() {
        calculateEntitlements();
        autoSaveEntitlements();
    });
    daysWorkedInput.addEventListener('input', function() {
        calculateEntitlements();
        autoSaveEntitlements();
    });
    monthlyDaysInput.addEventListener('input', function() {
        calculateEntitlements();
        autoSaveEntitlements();
    });
    
    // Initial calculation
    calculateEntitlements();
    
    // Auto-save function
    function autoSaveEntitlements() {
        const monthlyHours = parseFloat(monthlyHoursInput.value) || 0;
        const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
        const daysWorked = parseFloat(daysWorkedInput.value) || 0;
        const monthlyDays = parseFloat(monthlyDaysInput.value) || 0;
        
        // Calculate entitlements
        const dailyHours = monthlyDays > 0 ? monthlyHours / monthlyDays : 0;
        const actualHours = daysWorked * dailyHours;
        const entitlementsByHours = actualHours * hourlyRate;
        
        const fullSalary = monthlyHours * hourlyRate;
        const dailySalary = monthlyDays > 0 ? fullSalary / monthlyDays : 0;
        const entitlementsBySalary = daysWorked * dailySalary;
        
        const data = {
            employee_id: {{ $employee->id }},
            monthly_hours: monthlyHours,
            hourly_rate: hourlyRate,
            days_worked: daysWorked,
            monthly_days: monthlyDays,
            entitlements_by_hours: entitlementsByHours,
            entitlements_by_salary: entitlementsBySalary,
            notes: 'تم الحفظ تلقائياً عند التعديل',
            _token: '{{ csrf_token() }}'
        };
        
        // Save to database
        fetch('/api/employees/{{ $employee->id }}/entitlements', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            console.log('تم حفظ المستحقات تلقائياً');
        })
        .catch(error => {
            console.error('خطأ في حفظ المستحقات:', error);
        });
    }
    
    // Handle save entitlements form
    document.getElementById('save-entitlements-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const monthlyHours = parseFloat(monthlyHoursInput.value) || 0;
        const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
        const daysWorked = parseFloat(daysWorkedInput.value) || 0;
        const monthlyDays = parseFloat(monthlyDaysInput.value) || 0;
        const notes = '';
        
        const data = {
            monthly_hours: monthlyHours,
            hourly_rate: hourlyRate,
            days_worked: daysWorked,
            monthly_days: monthlyDays,
            notes: notes,
            _token: '{{ csrf_token() }}'
        };
        
        try {
            const response = await fetch('{{ route("employees.save-entitlements", $employee) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('تم حفظ نتائج المستحقات بنجاح!');
                document.getElementById('entitlements_notes').value = '';
            } else {
                alert('حدث خطأ أثناء حفظ النتائج: ' + (result.message || 'خطأ غير معروف'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ النتائج');
        }
    });
});
</script>