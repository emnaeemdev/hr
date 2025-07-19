@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة الأدوات</h3>
                    <a href="{{ route('tools.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة أداة جديدة
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
                            <form id="filterForm" method="GET" action="{{ route('tools.index') }}" class="row g-3">
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
                                    <label for="status_filter" class="form-label">فلتر حسب حالة الاستلام</label>
                                    <select name="status" id="status_filter" class="form-select">
                                        <option value="">جميع الأدوات</option>
                                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>استلم أدوات</option>
                                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>لم يستلم أدوات</option>
                                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">البحث</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="ابحث بالاسم أو الرقم التسلسلي..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('tools.index') }}" class="btn btn-secondary">
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
                                    <th>اسم الموظف</th>
                                <th>اسم الأداة</th>
                                <th>الرقم التسلسلي</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>تاريخ الشراء</th>
                                <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tools ?? [] as $tool)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tool->employee ? $tool->employee->name : '-' }}</td>
                                        <td>{{ $tool->name ?? 'غير محدد' }}</td>
                                        <td>{{ $tool->serial_number ?? 'غير محدد' }}</td>
                                        <td>{{ $tool->type ?? 'غير محدد' }}</td>
                                        <td>
                                            @if(($tool->status ?? 'available') == 'available')
                                                <span class="badge bg-success">متاحة</span>
                                            @elseif($tool->status == 'assigned')
                                                <span class="badge bg-warning">مُعارة</span>
                                            @else
                                                <span class="badge bg-danger">تحت الصيانة</span>
                                            @endif
                                        </td>
                                        <td>{{ $tool->purchase_date ? $tool->purchase_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('tools.show', $tool->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tools.edit', $tool->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tools.destroy', $tool->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الأداة؟')">
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
                                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد أدوات مسجلة حالياً</p>
                                            <a href="{{ route('tools.create') }}" class="btn btn-primary">
                                                إضافة أول أداة
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
    $('#branch_filter, #status_filter').on('change', function() {
        $('#filterForm').submit();
    });
});
</script>
@endpush
