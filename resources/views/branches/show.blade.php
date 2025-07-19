@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الفرع: {{ $branch->name }}</h4>
                    <div>
                        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اسم الفرع:</label>
                                <p class="form-control-plaintext">{{ $branch->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">كود الفرع:</label>
                                <p class="form-control-plaintext">{{ $branch->code ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">اسم المدير:</label>
                                <p class="form-control-plaintext">{{ $branch->manager_name ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">رقم الهاتف:</label>
                                <p class="form-control-plaintext">{{ $branch->phone ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($branch->address)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">العنوان:</label>
                                <p class="form-control-plaintext">{{ $branch->address }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                <p class="form-control-plaintext">{{ $branch->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">آخر تحديث:</label>
                                <p class="form-control-plaintext">{{ $branch->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الفرع؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Branch Employees -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">موظفي الفرع</h5>
                </div>
                <div class="card-body">
                    @if($branch->employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الهاتف</th>
                                        <th>المنصب</th>
                                        <th>تاريخ التوظيف</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($branch->employees as $employee)
                                        <tr>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->phone ?? 'غير محدد' }}</td>
                                            <td>{{ $employee->position ?? 'غير محدد' }}</td>
                                            <td>{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            <td>
                                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا يوجد موظفين في هذا الفرع</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection