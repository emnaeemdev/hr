@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إضافة طلب سلفة جديد</h4>
                    <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee-advances.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">الموظف <span class="text-danger">*</span></label>
                                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                                            id="employee_id" name="employee_id" required>
                                        <option value="">اختر الموظف</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                {{ (old('employee_id') == $employee->id || (isset($selectedEmployeeId) && $selectedEmployeeId == $employee->id)) ? 'selected' : '' }}>
                                                {{ $employee->name }} - {{ $employee->branch->name ?? 'بدون فرع' }}
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
                                    <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_date" class="form-label">تاريخ الطلب <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('request_date') is-invalid @enderror" 
                                           id="request_date" name="request_date" value="{{ old('request_date', date('Y-m-d')) }}" required>
                                    @error('request_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>مسدد</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">سبب السلفة</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($customFields->count() > 0)
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">الحقول المخصصة</h5>
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
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary">إلغاء</a>
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
@push('scripts')
<script>
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
    },
    matcher: function(params, data) {
        if ($.trim(params.term) === '') {
            return data;
        }

        function normalize(str) {
            return str
                .toLowerCase()
                .replace(/[أإآا]/g, 'ا')
                .replace(/ى/g, 'ي')
                .replace(/ؤ/g, 'و')
                .replace(/ئ/g, 'ي')
                .replace(/ة/g, 'ه')
                .replace(/\s+/g, '');
        }

        const term = normalize(params.term);
        const text = normalize(data.text);

        if (text.indexOf(term) > -1) {
            return data;
        }

        return null;
    }
});

</script>
@endpush


@endsection