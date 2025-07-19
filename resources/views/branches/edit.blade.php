@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تعديل الفرع: {{ $branch->name }}</h4>
                    <a href="{{ route('branches.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('branches.update', $branch) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الفرع <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">كود الفرع</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $branch->code) }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manager_name" class="form-label">اسم المدير</label>
                                    <input type="text" class="form-control @error('manager_name') is-invalid @enderror" 
                                           id="manager_name" name="manager_name" value="{{ old('manager_name', $branch->manager_name) }}">
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $branch->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', $branch->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Custom Fields -->
                            @if($customFields->count() > 0)
                                <div class="col-12">
                                    <h5 class="mb-3">الحقول المخصصة</h5>
                                </div>
                                @foreach($customFields as $customField)
                                    @php
                                        $existingValue = $branch->customFieldValues->where('custom_field_id', $customField->id)->first();
                                        $currentValue = $existingValue ? $existingValue->value : '';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="custom_field_{{ $customField->id }}" class="form-label">
                                                {{ $customField->name }}
                                                @if($customField->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            
                                            @if($customField->field_type === 'text')
                                                <input type="text" 
                                                       class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                       id="custom_field_{{ $customField->id }}" 
                                                       name="custom_field_{{ $customField->id }}" 
                                                       value="{{ old('custom_field_'.$customField->id, $currentValue) }}"
                                                       @if($customField->is_required) required @endif>
                                            @elseif($customField->field_type === 'number')
                                                <input type="number" 
                                                       class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                       id="custom_field_{{ $customField->id }}" 
                                                       name="custom_field_{{ $customField->id }}" 
                                                       value="{{ old('custom_field_'.$customField->id, $currentValue) }}"
                                                       @if($customField->is_required) required @endif>
                                            @elseif($customField->field_type === 'date')
                                                <input type="date" 
                                                       class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                       id="custom_field_{{ $customField->id }}" 
                                                       name="custom_field_{{ $customField->id }}" 
                                                       value="{{ old('custom_field_'.$customField->id, $currentValue) }}"
                                                       @if($customField->is_required) required @endif>
                                            @elseif($customField->field_type === 'textarea')
                                                <textarea class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                          id="custom_field_{{ $customField->id }}" 
                                                          name="custom_field_{{ $customField->id }}" 
                                                          rows="3"
                                                          @if($customField->is_required) required @endif>{{ old('custom_field_'.$customField->id, $currentValue) }}</textarea>
                                            @elseif($customField->field_type === 'select')
                                                <select class="form-control @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                        id="custom_field_{{ $customField->id }}" 
                                                        name="custom_field_{{ $customField->id }}"
                                                        @if($customField->is_required) required @endif>
                                                    <option value="">اختر...</option>
                                                    @if($customField->field_options)
                                                        @foreach(json_decode($customField->field_options) as $option)
                                                            <option value="{{ $option }}" 
                                                                    @if(old('custom_field_'.$customField->id, $currentValue) == $option) selected @endif>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @elseif($customField->field_type === 'checkbox')
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           class="form-check-input @error('custom_field_'.$customField->id) is-invalid @enderror" 
                                                           id="custom_field_{{ $customField->id }}" 
                                                           name="custom_field_{{ $customField->id }}" 
                                                           value="1"
                                                           @if(old('custom_field_'.$customField->id, $currentValue)) checked @endif>
                                                    <label class="form-check-label" for="custom_field_{{ $customField->id }}">
                                                        {{ $customField->name }}
                                                    </label>
                                                </div>
                                            @endif
                                            
                                            @error('custom_field_'.$customField->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('branches.index') }}" class="btn btn-secondary">إلغاء</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>تحديث الفرع
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection