@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل السلفة</h4>
                    <div>
                        <a href="{{ route('employee-advances.edit', $employeeAdvance) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('employee-advances.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">الموظف:</th>
                                    <td>{{ $employeeAdvance->employee->name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>المبلغ:</th>
                                    <td>{{ number_format($employeeAdvance->amount, 2) }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>المبلغ المتبقي:</th>
                                    <td>{{ number_format($employeeAdvance->remaining_amount, 2) }} جنيه</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">تاريخ الطلب:</th>
                                    <td>{{ $employeeAdvance->request_date ? \Carbon\Carbon::parse($employeeAdvance->request_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ السلفة:</th>
                                    <td>{{ $employeeAdvance->advance_date ? \Carbon\Carbon::parse($employeeAdvance->advance_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        @if($employeeAdvance->status == 'approved')
                                            <span class="badge bg-success">موافق عليها</span>
                                        @elseif($employeeAdvance->status == 'paid')
                                            <span class="badge bg-info">مدفوعة</span>
                                        @else
                                            <span class="badge bg-danger">مرفوضة</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($employeeAdvance->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>الملاحظات:</h6>
                                <p class="text-muted">{{ $employeeAdvance->notes }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('employee-advances.edit', $employeeAdvance) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> تعديل السلفة
                                </a>
                                <form action="{{ route('employee-advances.destroy', $employeeAdvance) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه السلفة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> حذف السلفة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection