@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة الفروع</h3>
                    <a href="{{ route('branches.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة فرع جديد
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>اسم الفرع</th>
                                    <th>العنوان</th>
                                    <th>رقم الهاتف</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches ?? [] as $branch)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $branch->name ?? 'غير محدد' }}</td>
                                        <td>{{ $branch->address ?? 'غير محدد' }}</td>
                                        <td>{{ $branch->phone ?? 'غير محدد' }}</td>
                                        <td>{{ $branch->created_at ? $branch->created_at->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('branches.show', $branch->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('branches.edit', $branch->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('branches.destroy', $branch->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفرع؟')">
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
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد فروع مسجلة حالياً</p>
                                            <a href="{{ route('branches.create') }}" class="btn btn-primary">
                                                إضافة أول فرع
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