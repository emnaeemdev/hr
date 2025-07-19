@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة السلف</h3>
                    <a href="{{ route('employee-advances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة سلفة جديدة
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
                            <form id="filterForm" method="GET" action="{{ route('employee-advances.index') }}" class="row g-3">
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
                                <div class="col-md-3">
                                    <label for="status_filter" class="form-label">فلتر حسب حالة السلفة</label>
                                    <select name="status" id="status_filter" class="form-select">
                                        <option value="">جميع السلف</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>استلم سلفة</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>لم يستلف</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">البحث</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="ابحث باسم الموظف..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary">
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
                                    <th>الموظف</th>
                                    <th>المبلغ</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المبلغ المتبقي</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الطلب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advances ?? [] as $advance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $advance->employee->name ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($advance->amount ?? 0, 2) }} جنيه</td>
                                        <td>{{ number_format($advance->paid_amount ?? 0, 2) }} جنيه</td>
                                        <td>{{ number_format(($advance->amount ?? 0) - ($advance->paid_amount ?? 0), 2) }} جنيه</td>
                                        <td>
                                            @if(($advance->status ?? 'pending') == 'pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @elseif($advance->status == 'approved')
                                                <span class="badge bg-success">موافق عليها</span>
                                            @elseif($advance->status == 'paid')
                                                <span class="badge bg-info">مدفوعة</span>
                                            @else
                                                <span class="badge bg-danger">مرفوضة</span>
                                            @endif
                                        </td>
                                        <td>{{ $advance->request_date ? $advance->request_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('employee-advances.show', $advance->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('employee-advances.edit', $advance->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('employee-advances.destroy', $advance->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه السلفة؟')">
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
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد سلف مسجلة حالياً</p>
                                            <a href="{{ route('employee-advances.create') }}" class="btn btn-primary">
                                                إضافة أول سلفة
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
    $('#branch_filter, #status_filter').on('change', function() {
        $('#filterForm').submit();
    });
});
</script>
@endpush
