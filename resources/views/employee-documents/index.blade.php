@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة وثائق الموظفين</h3>
                    <a href="{{ route('employee-documents.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة وثيقة جديدة
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
                            <form id="filterForm" method="GET" action="{{ route('employee-documents.index') }}" class="row g-3">
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
                                    <label for="status_filter" class="form-label">فلتر حسب حالة المستندات</label>
                                    <select name="status" id="status_filter" class="form-select">
                                        <option value="">جميع المستندات</option>
                                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>سلم مستندات</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>لم يسلم مستندات</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">البحث</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="ابحث باسم الموظف أو نوع الوثيقة..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('employee-documents.index') }}" class="btn btn-secondary">
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
                                    <th>نوع الوثيقة</th>
                                    <th>اسم الملف</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الرفع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents ?? [] as $document)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $document->employee->name ?? 'غير محدد' }}</td>
                                        <td>{{ $document->document_type ?? 'غير محدد' }}</td>
                                        <td>{{ $document->file_name ?? 'غير محدد' }}</td>
                                        <td>
                                            @if(($document->status ?? 'pending') == 'pending')
                                                <span class="badge bg-warning">قيد المراجعة</span>
                                            @elseif($document->status == 'verified')
                                                <span class="badge bg-success">مُتحقق منها</span>
                                            @else
                                                <span class="badge bg-danger">مرفوضة</span>
                                            @endif
                                        </td>
                                        <td>{{ $document->upload_date ? $document->upload_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('employee-documents.show', $document->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($document->file_path ?? false)
                                                    <a href="{{ url('storage/' . $document->file_path) }} "target="_blank"  class="btn btn-success btn-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('employee-documents.edit', $document->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('employee-documents.destroy', $document->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟')">
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
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد وثائق مرفوعة حالياً</p>
                                            <a href="{{ route('employee-documents.create') }}" class="btn btn-primary">
                                                إضافة أول وثيقة
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
