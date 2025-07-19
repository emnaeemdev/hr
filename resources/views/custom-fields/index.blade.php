@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إدارة الحقول المخصصة</h3>
                    <a href="{{ route('custom-fields.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة حقل جديد
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
                                    <th>اسم الحقل</th>
                                    <th>نوع الحقل</th>
                                    <th>الجدول المرتبط</th>
                                    <th>مطلوب</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customFields ?? [] as $field)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $field->field_name ?? 'غير محدد' }}</td>
                                        <td>
                                            @switch($field->field_type ?? 'text')
                                                @case('text')
                                                    <span class="badge bg-primary">نص</span>
                                                    @break
                                                @case('number')
                                                    <span class="badge bg-info">رقم</span>
                                                    @break
                                                @case('date')
                                                    <span class="badge bg-success">تاريخ</span>
                                                    @break
                                                @case('select')
                                                    <span class="badge bg-warning">قائمة منسدلة</span>
                                                    @break
                                                @case('textarea')
                                                    <span class="badge bg-secondary">نص طويل</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ $field->field_type }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $field->entity_type ?? 'غير محدد' }}</td>
                                        <td>
                                            @if($field->is_required ?? false)
                                                <span class="badge bg-danger">مطلوب</span>
                                            @else
                                                <span class="badge bg-secondary">اختياري</span>
                                            @endif
                                        </td>
                                        <td>{{ $field->created_at ? $field->created_at->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('custom-fields.show', $field->id ?? 1) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('custom-fields.edit', $field->id ?? 1) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('custom-fields.destroy', $field->id ?? 1) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحقل؟')">
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
                                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد حقول مخصصة حالياً</p>
                                            <a href="{{ route('custom-fields.create') }}" class="btn btn-primary">
                                                إضافة أول حقل مخصص
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