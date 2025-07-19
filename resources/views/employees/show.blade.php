@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Employee Details Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الموظف: {{ $employee->name }}</h4>
                    <div>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">الاسم:</th>
                                    <td>{{ $employee->name }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الهاتف:</th>
                                    <td>{{ $employee->phone ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>الفرع:</th>
                                    <td>{{ $employee->branch->name ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">المنصب:</th>
                                    <td>{{ $employee->position ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التوظيف:</th>
                                    <td>{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>ساعات العمل الإجمالية:</th>
                                    <td>{{ $employee->work_hours ?? 8 }} ساعة</td>
                                </tr>
                                <tr>
                                    <th>الراتب الشهري:</th>
                                    <td>{{ $employee->monthly_salary ? number_format($employee->monthly_salary, 2) . ' جنيه' : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>إجمالي السلف:</th>
                                    <td class="text-danger">{{ number_format($employee->total_remaining_advances, 2) }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>الراتب الصافي:</th>
                                    <td class="text-success fw-bold">{{ number_format($employee->net_salary, 2) }} جنيه</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Custom Fields -->
                    @if($employee->customFieldValues && $employee->customFieldValues->count() > 0)
                        <hr>
                        <h5 class="mb-3">الحقول المخصصة</h5>
                        <div class="row">
                            @foreach($employee->customFieldValues as $fieldValue)
                                <div class="col-md-6 mb-3">
                                    <strong>{{ $fieldValue->customField->label }}:</strong>
                                    <span class="text-muted">
                                        @if($fieldValue->customField->field_type == 'checkbox')
                                            {{ $fieldValue->value ? 'نعم' : 'لا' }}
                                        @else
                                            {{ $fieldValue->value ?: 'غير محدد' }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Advances -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">السلف</h5>
                    <a href="{{ route('employee-advances.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> إضافة سلفة
                    </a>
                </div>
                <div class="card-body">
                    @if($employee->advances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>المبلغ</th>
                                        <th>المبلغ المتبقي</th>
                                        <th>تاريخ الطلب</th>
                                        <th>السبب</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->advances as $advance)
                                        <tr>
                                            <td>{{ number_format($advance->amount, 2) }} جنيه</td>
                                            <td>{{ number_format($advance->remaining_amount, 2) }} جنيه</td>
                                            <td>{{ $advance->request_date->format('Y-m-d') }}</td>
                                            <td>{{ $advance->reason ?? 'غير محدد' }}</td>
                                            <td>
                                                @switch($advance->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">في الانتظار</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">موافق عليها</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">مرفوضة</span>
                                                        @break
                                                    @case('paid')
                                                        <span class="badge bg-info">مدفوعة</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('employee-advances.show', $advance) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد سلف لهذا الموظف</p>
                    @endif
                </div>
            </div>

            <!-- Employee Documents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">المستندات</h5>
                    <a href="{{ route('employee-documents.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> إضافة مستند
                    </a>
                </div>
                <div class="card-body">
                    @if($employee->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>نوع المستند</th>
                                        <th>اسم الملف</th>
                                        <th>تاريخ الرفع</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->documents as $document)
                                        <tr>
                                            <td>{{ $document->document_type }}</td>
                                            <td>{{ $document->file_name }}</td>
                                            <td>{{ $document->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if($document->status == 'verified')
                                                    <span class="badge bg-success">مُتحقق منها</span>
                                                @elseif($document->status == 'pending')
                                                    <span class="badge bg-warning">معلق</span>
                                                @elseif($document->status == 'rejected')
                                                    <span class="badge bg-danger">مرفوض</span>
                                                @else
                                                    <span class="badge bg-secondary">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('employee-documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد مستندات لهذا الموظف</p>
                    @endif
                </div>
            </div>

            <!-- Employee Tools -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الأدوات المخصصة</h5>
                    <a href="{{ route('tools.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> تخصيص أداة
                    </a>
                </div>
                <div class="card-body">
                    @if($employee->assignedTools->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الأداة</th>
                                        <th>الرقم التسلسلي</th>
                                        <th>النوع</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الشراء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->assignedTools as $tool)
                                        <tr>
                                            <td>{{ $tool->name }}</td>
                                            <td>{{ $tool->serial_number ?? 'غير محدد' }}</td>
                                            <td>{{ $tool->type ?? 'غير محدد' }}</td>
                                            <td>
                                                @switch($tool->status)
                                                    @case('available')
                                                        <span class="badge bg-success">متاحة</span>
                                                        @break
                                                    @case('assigned')
                                                        <span class="badge bg-warning">مخصصة</span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="badge bg-danger">صيانة</span>
                                                        @break
                                                    @case('damaged')
                                                        <span class="badge bg-dark">تالفة</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $tool->purchase_date ? $tool->purchase_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            <td>
                                                <a href="{{ route('tools.show', $tool) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد أدوات مخصصة لهذا الموظف</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection