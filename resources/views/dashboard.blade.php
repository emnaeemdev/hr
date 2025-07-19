@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="mb-0">مرحباً، {{ auth()->user()->name }}</h2>
                    <p class="mb-0">لوحة التحكم - نظام إدارة الموظفين والأدوات</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('employees.index') }}" class="text-decoration-none">
                <div class="card border-left-primary shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي الموظفين
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Employee::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('branches.index') }}" class="text-decoration-none">
                <div class="card border-left-success shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    إجمالي الفروع
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Branch::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('tools.index') }}" class="text-decoration-none">
                <div class="card border-left-info shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    إجمالي الأدوات
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Tool::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tools fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('employee-advances.index') }}" class="text-decoration-none">
                <div class="card border-left-warning shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    إجمالي السلف
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\EmployeeAdvance::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الإجراءات السريعة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('employees.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus mb-2"></i><br>
                                إضافة موظف
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('branches.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-building mb-2"></i><br>
                                إضافة فرع
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('tools.create') }}" class="btn btn-info w-100">
                                <i class="fas fa-tools mb-2"></i><br>
                                إضافة أداة
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('employee-advances.create') }}" class="btn btn-warning w-100">
                                <i class="fas fa-money-bill-wave mb-2"></i><br>
                                طلب سلفة
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('employee-documents.create') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-file-upload mb-2"></i><br>
                                رفع مستند
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('custom-fields.create') }}" class="btn btn-dark w-100">
                                <i class="fas fa-plus-circle mb-2"></i><br>
                                حقل مخصص
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">أحدث الموظفين</h5>
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @php
                        $recentEmployees = \App\Models\Employee::with('branch')->latest()->take(5)->get();
                    @endphp
                    @if($recentEmployees->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentEmployees as $employee)
                                <a href="{{ route('employees.show', $employee->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-decoration-none">
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ $employee->name }}</h6>
                                        <p class="mb-1 text-muted">{{ $employee->position }}</p>
                                        <small class="text-muted">{{ $employee->branch->name ?? 'غير محدد' }}</small>
                                    </div>
                                    <small class="text-muted">{{ $employee->created_at->diffForHumans() }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">لا توجد بيانات موظفين</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">أحدث السلف</h5>
                    <a href="{{ route('employee-advances.index') }}" class="btn btn-sm btn-outline-warning">عرض الكل</a>
                </div>
                <div class="card-body">
                    @php
                        $recentAdvances = \App\Models\EmployeeAdvance::with('employee')->latest()->take(5)->get();
                    @endphp
                    @if($recentAdvances->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentAdvances as $advance)
                                <a href="{{ route('employee-advances.show', $advance->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-decoration-none">
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ $advance->employee->name ?? 'غير محدد' }}</h6>
                                        <p class="mb-1 text-dark">{{ number_format($advance->amount, 2) }} جنيه</p>
                                        <small class="text-muted">{{ $advance->reason }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($advance->status == 'pending')
                                            <span class="badge bg-warning">معلق</span>
                                        @elseif($advance->status == 'approved')
                                            <span class="badge bg-success">موافق عليه</span>
                                        @else
                                            <span class="badge bg-danger">مرفوض</span>
                                        @endif
                                        <br><small class="text-muted">{{ $advance->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">لا توجد طلبات سلف</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection
