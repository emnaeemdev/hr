@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل المستند: {{ $employeeDocument->document_name }}</h4>
                    <div>
                        <a href="{{ route('employee-documents.edit', $employeeDocument) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('employee-documents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الموظف:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->employee->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">نوع المستند:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->document_type }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اسم المستند:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->document_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الحالة:</label>
                                <p class="form-control-plaintext">
                                    @if($employeeDocument->status == 'verified')
                                        <span class="badge bg-success">مُتحقق منها</span>
                                    @elseif($employeeDocument->status == 'pending')
                                        <span class="badge bg-warning">معلق</span>
                                    @elseif($employeeDocument->status == 'rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                    @else
                                        <span class="badge bg-secondary">غير محدد</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإصدار:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->issue_date ? $employeeDocument->issue_date->format('Y-m-d') : 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الانتهاء:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->expiry_date ? $employeeDocument->expiry_date->format('Y-m-d') : 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($employeeDocument->file_path)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الملف:</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file me-2"></i>
                                    <span class="me-3">{{ basename($employeeDocument->file_path) }}</span>
                                    <a href="{{ route('employee-documents.download', $employeeDocument) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-download me-1"></i>تحميل
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($employeeDocument->notes)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ملاحظات:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">آخر تحديث:</label>
                                <p class="form-control-plaintext">{{ $employeeDocument->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('employee-documents.edit', $employeeDocument) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                        <form action="{{ route('employee-documents.destroy', $employeeDocument) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستند؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection