@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إضافة مستند جديد</h4>
                    <a href="{{ route('employee-documents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee-documents.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="document_type" class="form-label">نوع المستند <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('document_type') is-invalid @enderror" 
                                           id="document_type" name="document_type" value="{{ old('document_type') }}" 
                                           placeholder="مثل: هوية، جواز سفر، شهادة" required>
                                    @error('document_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_name" class="form-label">اسم المستند</label>
                                    <input type="text" class="form-control @error('document_name') is-invalid @enderror" 
                                           id="document_name" name="document_name" value="{{ old('document_name') }}" 
                                           placeholder="اختياري - يمكن ترك هذا الحقل فارغاً">
                                    @error('document_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file_path" class="form-label">ملف المستند</label>
                                    <input type="file" class="form-control @error('file_path') is-invalid @enderror" 
                                           id="file_path" name="file_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">الملفات المسموحة: PDF, DOC, DOCX, JPG, PNG (حد أقصى 2MB)</div>
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">تاريخ الإصدار</label>
                                    <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                           id="issue_date" name="issue_date" value="{{ old('issue_date') }}">
                                    @error('issue_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">تاريخ الانتهاء</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                        <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>تم التحقق</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Fields -->
                        @if($customFields->count() > 0)
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0">الحقول المخصصة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($customFields as $customField)
                                            <div class="col-md-6 mb-3">
                                                <label for="custom_field_{{ $customField->id }}" class="form-label">
                                                    {{ $customField->field_name }}
                                                    @if($customField->is_required)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                
                                                @if($customField->field_type === 'text')
                                                    <input type="text" 
                                                           class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                           id="custom_field_{{ $customField->id }}" 
                                                           name="custom_field_{{ $customField->id }}" 
                                                           value="{{ old('custom_field_'.$customField->id) }}"
                                                           {{ $customField->is_required ? 'required' : '' }}>
                                                
                                                @elseif($customField->field_type === 'number')
                                                    <input type="number" 
                                                           class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                           id="custom_field_{{ $customField->id }}" 
                                                           name="custom_field_{{ $customField->id }}" 
                                                           value="{{ old('custom_field_'.$customField->id) }}"
                                                           {{ $customField->is_required ? 'required' : '' }}>
                                                
                                                @elseif($customField->field_type === 'date')
                                                    <input type="date" 
                                                           class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                           id="custom_field_{{ $customField->id }}" 
                                                           name="custom_field_{{ $customField->id }}" 
                                                           value="{{ old('custom_field_'.$customField->id) }}"
                                                           {{ $customField->is_required ? 'required' : '' }}>
                                                
                                                @elseif($customField->field_type === 'textarea')
                                                    <textarea class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                              id="custom_field_{{ $customField->id }}" 
                                                              name="custom_field_{{ $customField->id }}" 
                                                              rows="3"
                                                              {{ $customField->is_required ? 'required' : '' }}>{{ old('custom_field_'.$customField->id) }}</textarea>
                                                
                                                @elseif($customField->field_type === 'select')
                                                    <select class="form-select @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                            id="custom_field_{{ $customField->id }}" 
                                                            name="custom_field_{{ $customField->id }}"
                                                            {{ $customField->is_required ? 'required' : '' }}>
                                                        <option value="">اختر...</option>
                                                        @if($customField->field_options)
                                                            @foreach(explode(',', $customField->field_options) as $option)
                                                                <option value="{{ trim($option) }}" 
                                                                    {{ old('custom_field_'.$customField->id) == trim($option) ? 'selected' : '' }}>
                                                                    {{ trim($option) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                
                                                @elseif($customField->field_type === 'checkbox')
                                                    <div class="form-check">
                                                        <input class="form-check-input @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                               type="checkbox" 
                                                               id="custom_field_{{ $customField->id }}" 
                                                               name="custom_field_{{ $customField->id }}" 
                                                               value="1"
                                                               {{ old('custom_field_'.$customField->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="custom_field_{{ $customField->id }}">
                                                            {{ $customField->field_name }}
                                                        </label>
                                                    </div>
                                                @endif
                                                
                                                @error('custom_field_'.$customField->id)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('employee-documents.index') }}" class="btn btn-secondary">إلغاء</a>
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