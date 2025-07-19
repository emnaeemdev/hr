@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة الموظفين</h3>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة موظف جديد
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="filterForm" method="GET" action="{{ route('employees.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="branch_filter" class="form-label">فلتر حسب الفرع</label>
                                    <select name="branch_id" id="branch_filter" class="form-select">
                                        <option value="">جميع الفروع</option>
                                        @foreach(\App\Models\Branch::all() as $branch)
                                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="advance_filter" class="form-label">فلتر حسب السلف</label>
                                    <select name="has_advances" id="advance_filter" class="form-select">
                                        <option value="">جميع الموظفين</option>
                                        <option value="1" {{ request('has_advances') == '1' ? 'selected' : '' }}>الموظفين الذين لديهم سلف</option>
                                        <option value="0" {{ request('has_advances') == '0' ? 'selected' : '' }}>الموظفين بدون سلف</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tools_filter" class="form-label">فلتر حسب الأدوات</label>
                                    <select name="has_tools" id="tools_filter" class="form-select">
                                        <option value="">جميع الموظفين</option>
                                        <option value="1" {{ request('has_tools') == '1' ? 'selected' : '' }}>استلموا أدوات</option>
                                        <option value="0" {{ request('has_tools') == '0' ? 'selected' : '' }}>لم يستلموا أدوات</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="documents_filter" class="form-label">فلتر حسب المستندات</label>
                                    <select name="has_documents" id="documents_filter" class="form-select">
                                        <option value="">جميع الموظفين</option>
                                        <option value="1" {{ request('has_documents') == '1' ? 'selected' : '' }}>سلموا مستندات</option>
                                        <option value="0" {{ request('has_documents') == '0' ? 'selected' : '' }}>لم يسلموا مستندات</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="search" class="form-label">البحث بالاسم</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="ابحث بالاسم..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> إعادة تعيين
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>رقم الهاتف</th>
                                    <th>الفرع</th>
                                    <th>المنصب</th>
                                    <th>ساعات العمل الإجمالية</th>
                                    <th>الراتب الشهري</th>
                                    <th>إجمالي السلف</th>
                                    <th>الراتب الصافي</th>
                                    <th>الأدوات</th>
                                    <th>المستندات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees ?? [] as $employee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $employee->name ?? 'غير محدد' }}</td>
                                        <td>{{ $employee->phone ?? 'غير محدد' }}</td>
                                        <td>{{ $employee->branch->name ?? 'غير محدد' }}</td>
                                        <td>{{ $employee->position ?? 'غير محدد' }}</td>
                                        <td>{{ $employee->work_hours ?? 8 }} ساعة</td>
                                        <td>{{ $employee->monthly_salary ? number_format($employee->monthly_salary, 2) : '0.00' }} جنيه</td>
                                        <td class="text-danger">{{ number_format($employee->total_remaining_advances, 2) }} جنيه</td>
                                        <td class="text-success fw-bold">{{ number_format($employee->net_salary, 2) }} جنيه</td>
                                        <td class="text-center">
                                            @php
                                                $assignedToolsCount = \App\Models\Tool::where('assigned_employee_id', $employee->id)->where('status', 'assigned')->count();
                                            @endphp
                                            @if($assignedToolsCount > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> استلم ({{ $assignedToolsCount }})
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> لم يستلم
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $documentsCount = $employee->documents ? $employee->documents->count() : 0;
                                                $pendingDocuments = $employee->documents ? $employee->documents->where('status', 'pending')->count() : 0;
                                            @endphp
                                            @if($pendingDocuments > 0)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i> معلق ({{ $documentsCount }})
                                                </span>
                                            @elseif($documentsCount > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> سلم ({{ $documentsCount }})
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> لم يسلم
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('employees.show', $employee->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('employees.edit', $employee->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('employees.destroy', $employee->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا يوجد موظفون مسجلون حالياً</p>
                                            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                                                إضافة أول موظف
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form when filters change (dropdowns only)
    $('#branch_filter, #advance_filter, #tools_filter, #documents_filter').on('change', function() {
        $('#filterForm').submit();
    });
});
</script>
@endpush
