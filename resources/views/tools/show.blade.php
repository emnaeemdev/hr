@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الأداة</h4>
                    <div>
                        <a href="{{ route('tools.edit', $tool) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                        <a href="{{ route('tools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اسم الأداة:</label>
                                <p class="form-control-plaintext">{{ $tool->name ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الرقم التسلسلي:</label>
                                <p class="form-control-plaintext">{{ $tool->serial_number ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف:</label>
                        <p class="form-control-plaintext">{{ $tool->description ?? 'لا يوجد وصف' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الشراء:</label>
                                <p class="form-control-plaintext">{{ $tool->purchase_date ? $tool->purchase_date->format('Y-m-d') : 'غير محدد' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">سعر الشراء:</label>
                                <p class="form-control-plaintext">{{ $tool->purchase_price ? number_format($tool->purchase_price, 2) . ' جنيه' : 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الحالة:</label>
                                <p class="form-control-plaintext">
                                    @if(($tool->status ?? 'available') == 'available')
                                        <span class="badge bg-success">متاحة</span>
                                    @elseif($tool->status == 'assigned')
                                        <span class="badge bg-warning">مُعارة</span>
                                    @elseif($tool->status == 'maintenance')
                                        <span class="badge bg-info">تحت الصيانة</span>
                                    @else
                                        <span class="badge bg-danger">تالفة</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الموظف المخصص له:</label>
                                <p class="form-control-plaintext">
                                    @if($tool->employee)
                                        <a href="{{ route('employees.show', $tool->employee) }}" class="text-decoration-none">
                                            {{ $tool->employee->name }}
                                        </a>
                                    @else
                                        غير مخصص
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                <p class="form-control-plaintext">{{ $tool->created_at ? $tool->created_at->format('Y-m-d H:i') : 'غير محدد' }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">آخر تحديث:</label>
                                <p class="form-control-plaintext">{{ $tool->updated_at ? $tool->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($tool->status == 'assigned' && $tool->employee)
                        <div class="mt-4">
                            <h5>إجراءات الأداة</h5>
                            <form action="{{ route('tools.return', $tool) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من استرداد هذه الأداة؟')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-undo me-2"></i>استرداد الأداة
                                </button>
                            </form>
                        </div>
                    @elseif($tool->status == 'available')
                        <div class="mt-4">
                            <h5>إجراءات الأداة</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignToolModal">
                                <i class="fas fa-user-plus me-2"></i>تخصيص للموظف
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Custom Fields -->
                @if($tool->customFieldValues && $tool->customFieldValues->count() > 0)
                    <hr>
                    <h5 class="mb-3">الحقول المخصصة</h5>
                    <div class="row">
                        @foreach($tool->customFieldValues as $fieldValue)
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ $fieldValue->customField->label }}:</label>
                                <p class="form-control-plaintext">
                                    @if($fieldValue->customField->field_type == 'checkbox')
                                        {{ $fieldValue->value ? 'نعم' : 'لا' }}
                                    @else
                                        {{ $fieldValue->value ?: 'غير محدد' }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Tool Modal -->
@if($tool->status == 'available')
<div class="modal fade" id="assignToolModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تخصيص الأداة للموظف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tools.assign', $tool) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_employee_id" class="form-label">اختر الموظف</label>
                        <select class="form-select" id="assigned_employee_id" name="assigned_employee_id" required>
                            <option value="">اختر موظف</option>
                            @foreach(\App\Models\Employee::all() as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تخصيص</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
$(document).ready(function() {
    $('#assigned_employee_id').select2({
        placeholder: 'اختر موظف',
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
            $('#employee_id').on('change', function () {
            this.form.submit(); // أو أي كود كان بيعمل فلترة
        });
});
</script>
@endsection