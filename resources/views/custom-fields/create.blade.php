@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إضافة حقل مخصص جديد</h4>
                    <a href="{{ route('custom-fields.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('custom-fields.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الحقل (بالإنجليزية) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="مثل: emergency_contact" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="label" class="form-label">تسمية الحقل <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('label') is-invalid @enderror" 
                                           id="label" name="label" value="{{ old('label') }}" 
                                           placeholder="مثل: جهة الاتصال في حالات الطوارئ" required>
                                    @error('label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="field_type" class="form-label">نوع الحقل <span class="text-danger">*</span></label>
                                    <select class="form-select @error('field_type') is-invalid @enderror" 
                                            id="field_type" name="field_type" required>
                                        <option value="">اختر نوع الحقل</option>
                                        <option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>{{ __('messages.text') }}</option>
                        <option value="number" {{ old('field_type') == 'number' ? 'selected' : '' }}>{{ __('messages.number') }}</option>
                        <option value="date" {{ old('field_type') == 'date' ? 'selected' : '' }}>{{ __('messages.date') }}</option>
                        <option value="select" {{ old('field_type') == 'select' ? 'selected' : '' }}>{{ __('messages.select') }}</option>
                        <option value="checkbox" {{ old('field_type') == 'checkbox' ? 'selected' : '' }}>{{ __('messages.checkbox') }}</option>
                                    </select>
                                    @error('field_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="applies_to" class="form-label">ينطبق على <span class="text-danger">*</span></label>
                                    <select class="form-select @error('applies_to') is-invalid @enderror" id="applies_to" name="applies_to" required>
                                        <option value="">اختر النطاق</option>
                                        <option value="employee" {{ old('applies_to') == 'employee' ? 'selected' : '' }}>{{ __('messages.employee') }}</option>
                        <option value="branch" {{ old('applies_to') == 'branch' ? 'selected' : '' }}>{{ __('messages.branch') }}</option>
                        <option value="tool" {{ old('applies_to') == 'tool' ? 'selected' : '' }}>{{ __('messages.tool') }}</option>
                        <option value="advance" {{ old('applies_to') == 'advance' ? 'selected' : '' }}>{{ __('messages.advance') }}</option>
                        <option value="document" {{ old('applies_to') == 'document' ? 'selected' : '' }}>{{ __('messages.document') }}</option>
                                    </select>
                                    @error('applies_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="options_container" style="display: none;">
                            <label for="options" class="form-label">خيارات الحقل</label>
                            <textarea class="form-control @error('options') is-invalid @enderror" 
                                      id="options" name="options" rows="3" 
                                      placeholder="اكتب كل خيار في سطر منفصل">{{ old('options') }}</textarea>
                            <div class="form-text">للقوائم المنسدلة، اكتب كل خيار في سطر منفصل</div>
                            @error('options')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input type="hidden" name="is_required" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1"
                                           {{ old('is_required') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        حقل مطلوب
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        مفعل
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('custom-fields.index') }}" class="btn btn-secondary">إلغاء</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldType = document.getElementById('field_type');
    const optionsContainer = document.getElementById('options_container');
    
    function toggleOptions() {
        const value = fieldType.value;
        if (value === 'select') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }
    
    fieldType.addEventListener('change', toggleOptions);
    toggleOptions(); // Initial check
});
</script>
@endsection