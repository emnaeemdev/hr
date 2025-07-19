@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الحقل المخصص: {{ $customField->label }}</h4>
                    <div>
                        <a href="{{ route('custom-fields.edit', $customField) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('custom-fields.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">اسم الحقل:</th>
                                    <td>{{ $customField->name }}</td>
                                </tr>
                                <tr>
                                    <th>التسمية:</th>
                                    <td>{{ $customField->label }}</td>
                                </tr>
                                <tr>
                                    <th>نوع الحقل:</th>
                                    <td>
                                        @switch($customField->field_type)
                                            @case('text')
                                                نص
                                                @break
                                            @case('number')
                                                رقم
                                                @break
                                            @case('email')
                                                بريد إلكتروني
                                                @break
                                            @case('date')
                                                تاريخ
                                                @break
                                            @case('select')
                                                قائمة منسدلة
                                                @break
                                            @case('textarea')
                                                نص طويل
                                                @break
                                            @case('checkbox')
                                                مربع اختيار
                                                @break
                                            @case('radio')
                                                اختيار واحد
                                                @break
                                            @default
                                                {{ $customField->field_type }}
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>ينطبق على:</th>
                                    <td>
                                        @switch($customField->applies_to)
                                            @case('employee')
                                                الموظفين
                                                @break
                                            @case('branch')
                                                الفروع
                                                @break
                                            @case('tool')
                                                الأدوات
                                                @break
                                            @case('document')
                                                المستندات
                                                @break
                                            @default
                                                {{ $customField->applies_to }}
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>مطلوب:</th>
                                    <td>
                                        @if($customField->is_required)
                                            <span class="badge bg-danger">نعم</span>
                                        @else
                                            <span class="badge bg-secondary">لا</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>نشط:</th>
                                    <td>
                                        @if($customField->is_active)
                                            <span class="badge bg-success">نعم</span>
                                        @else
                                            <span class="badge bg-danger">لا</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض:</th>
                                    <td>{{ $customField->sort_order ?? 'غير محدد' }}</td>
                                </tr>
                                @if($customField->options && count($customField->options) > 0)
                                <tr>
                                    <th>الخيارات:</th>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($customField->options as $option)
                                                <li><i class="fas fa-circle fa-xs me-2"></i>{{ $option }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $customField->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $customField->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection