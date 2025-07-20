<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الموظف - {{ $employee->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            direction: rtl;
            text-align: right;
        }
        
        .print-container {
            max-width: 210mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .print-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .print-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        }
        
        .print-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        
        .print-header p {
            font-size: 1.1rem;
            margin: 10px 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .print-content {
            padding: 40px;
        }
        
        .employee-info {
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 3px solid #34495e;
        }
        
        .employee-info h2 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border-right: 5px solid #3498db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #34495e;
            font-size: 1.1rem;
        }
        
        .section {
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .section-header {
            background: #f8f9fa;
            color: #495057;
            padding: 15px 20px;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .section-content {
            padding: 30px;
        }
        
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 0;
        }
        
        .table th {
            background: #f8f9fa;
            color: #495057;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #dee2e6;
        }
        
        .table td {
            padding: 10px 8px;
            border: 1px solid #dee2e6;
            text-align: center;
            background: white;
        }
        
        .table tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }
        
        .entitlements-calculator {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .entitlements-calculator h4 {
            color: #495057;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .calc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .calc-section {
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .calc-section-title {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1rem;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        
        .calc-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        
        .calc-item.calc-result {
            background: #e9ecef;
            border-color: #6c757d;
        }
        
        .calc-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        
        .calc-value {
            font-size: 1.1rem;
            color: #495057;
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .calc-formula {
            font-size: 0.75rem;
            color: #6c757d;
            font-style: italic;
        }
        
        .calc-result .calc-value {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .print-footer {
            background: #f8f9fa;
            color: #495057;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .footer-item {
            text-align: center;
        }
        
        .footer-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .footer-value {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .print-buttons {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .print-btn {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        
        .print-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
            text-decoration: none;
        }
        
        .no-data {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #bdc3c7;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                font-size: 12px;
            }
            
            .print-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .print-buttons {
                display: none !important;
            }
            
            .section {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }
            
            .print-header {
                page-break-after: avoid;
            }
            
            .table {
                font-size: 10px;
            }
            
            .table th, .table td {
                padding: 8px 5px;
            }
            
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .calc-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .footer-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>
<body>
    <!-- Print Buttons -->
    <div class="print-buttons">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> طباعة التقرير
        </button>
        <a href="{{ route('employees.show', $employee->id) }}" class="print-btn">
            <i class="fas fa-arrow-left"></i> العودة للموظف
        </a>
        <a href="{{ route('employees.index') }}" class="print-btn">
            <i class="fas fa-list"></i> قائمة الموظفين
        </a>
    </div>

    <div class="print-container">
        <!-- Print Header -->
        <div class="print-header">
            <h1><i class="fas fa-user-tie"></i> تقرير تفاصيل الموظف</h1>
            <p>تاريخ الطباعة: {{ date('Y-m-d H:i:s') }}</p>
        </div>

        <div class="print-content">
            @php
                // جلب آخر حساب محفوظ للمستحقات من قاعدة البيانات
                $latestEntitlement = $employee->latestEntitlement;
                
                if ($latestEntitlement) {
                    // استخدام القيم المحفوظة
                    $savedMonthlyHours = $latestEntitlement->monthly_hours;
                    $savedMonthlyDays = $latestEntitlement->monthly_days;
                    $savedHourlyRate = $latestEntitlement->hourly_rate;
                    $savedDaysWorked = $latestEntitlement->days_worked;
                    $savedDailyHours = $latestEntitlement->daily_hours;
                    $savedActualHours = $latestEntitlement->actual_hours;
                    $savedEntitlementsByHours = $latestEntitlement->entitlements_by_hours;
                    $savedFullSalary = $latestEntitlement->full_salary;
                    $savedDailySalary = $latestEntitlement->daily_salary;
                    $savedEntitlementsBySalary = $latestEntitlement->entitlements_by_salary;
                    $savedNetSalaryByHours = $latestEntitlement->net_salary_by_hours;
                    $savedNetSalaryBySalary = $latestEntitlement->net_salary_by_salary;
                    $savedTotalAdvances = $latestEntitlement->total_advances;
                    $savedNotes = $latestEntitlement->notes ?? '';
                } else {
                    // القيم الافتراضية في حالة عدم وجود حساب محفوظ
                    $savedMonthlyHours = 208;
                    $savedMonthlyDays = 26;
                    $savedHourlyRate = 36.06;
                    $savedDaysWorked = 26;
                    
                    // الحسابات الافتراضية
                    $savedDailyHours = $savedMonthlyHours / $savedMonthlyDays;
                    $savedActualHours = $savedDaysWorked * $savedDailyHours;
                    $savedEntitlementsByHours = $savedActualHours * $savedHourlyRate;
                    
                    $savedFullSalary = $savedMonthlyHours * $savedHourlyRate;
                    $savedDailySalary = $savedFullSalary / $savedMonthlyDays;
                    $savedEntitlementsBySalary = $savedDailySalary * $savedDaysWorked;
                    
                    // حساب إجمالي السلف المتبقية
                    $savedTotalAdvances = $employee->advances->where('status', '!=', 'rejected')->sum(function($advance) {
                        return $advance->amount - $advance->paid_amount;
                    });
                    
                    // حساب الراتب الصافي (بعد خصم السلف)
                    $savedNetSalaryByHours = max(0, $savedEntitlementsByHours - $savedTotalAdvances);
                    $savedNetSalaryBySalary = max(0, $savedEntitlementsBySalary - $savedTotalAdvances);
                    $savedNotes = '';
                }
            @endphp

            <!-- Employee Information -->
            <div class="employee-info">
                <h2><i class="fas fa-id-card"></i> {{ $employee->name }}</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-building"></i> الفرع</div>
                        <div class="info-value">{{ $employee->branch->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-briefcase"></i> الوظيفة</div>
                        <div class="info-value">{{ $employee->position ?? 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone"></i> رقم الهاتف</div>
                        <div class="info-value">{{ $employee->phone ?? 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-calendar-alt"></i> تاريخ التوظيف</div>
                        <div class="info-value">{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill-wave"></i> الراتب الشهري</div>
                        <div class="info-value">{{ number_format(round($savedFullSalary), 0, '.', ',') }} جنيه</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-hand-holding-usd"></i> إجمالي السلف</div>
                        <div class="info-value">{{ number_format($savedTotalAdvances, 0, '.', ',') }} جنيه</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-wallet"></i> الراتب الصافي</div>
                        <div class="info-value">{{ number_format(round($savedNetSalaryBySalary), 0, '.', ',') }} جنيه</div>
                    </div>

                </div>
            </div>

            <!-- Entitlements Calculator -->
            
            <div class="entitlements-calculator">
                <h4><i class="fas fa-calculator"></i> حاسبة المستحقات</h4>
                
                @if($latestEntitlement)
                    <div class="calc-info">
                        <p><strong>آخر تحديث:</strong> {{ $latestEntitlement->created_at->format('Y-m-d H:i:s') }}</p>
                        @if($savedNotes)
                            <p><strong>ملاحظات:</strong> {{ $savedNotes }}</p>
                        @endif
                    </div>
                @else
                    <div class="calc-info">
                        <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> لم يتم حفظ أي حساب مستحقات بعد - القيم المعروضة افتراضية</p>
                    </div>
                @endif
                
                <!-- الطريقة الأولى: حساب بعدد الساعات -->
                <div class="calc-section">
                    <h6 class="calc-section-title">حساب المستحقات</h6>
                    <div class="calc-grid">
                        <div class="calc-item">
                            <div class="calc-label">ساعات اليوم الواحد</div>
                            <div class="calc-value">{{ number_format($savedDailyHours, 0, '.', ',') }} ساعة</div>
                            <div class="calc-formula">{{ $savedMonthlyHours }} ÷ {{ $savedMonthlyDays }}</div>
                        </div>
                        <div class="calc-item">
                            <div class="calc-label">إجمالي الساعات الفعلية</div>
                            <div class="calc-value">{{ number_format($savedActualHours, 0, '.', ',') }} ساعة</div>
                <div class="calc-formula">{{ $savedDaysWorked }} × {{ number_format($savedDailyHours, 0, '.', ',') }}</div>
                        </div>
                        <div class="calc-item calc-result">
                            <div class="calc-label">المستحقات</div>
                            <div class="calc-value">{{ number_format(round($savedEntitlementsByHours), 0, '.', ',') }} جنيه</div>
                <div class="calc-formula">{{ number_format($savedActualHours, 0, '.', ',') }} × {{ $savedHourlyRate }}</div>
                        </div>
                        <div class="calc-item calc-result">
                            <div class="calc-label">الراتب الصافي</div>
                            <div class="calc-value">{{ number_format(round($savedNetSalaryByHours), 0, '.', ',') }} جنيه</div>
                <div class="calc-formula">بعد خصم السلف ({{ number_format($savedTotalAdvances, 0, '.', ',') }} جنيه)</div>
                        </div>
                    </div>
                </div>
                
                <!-- 
                <div class="calc-section">
                    <h6 class="calc-section-title">الطريقة الثانية: حساب بالراتب الكامل</h6>
                    <div class="calc-grid">
                        <div class="calc-item">
                            <div class="calc-label">الراتب الكامل</div>
                            <div class="calc-value">{{ number_format(round($savedFullSalary), 0, '.', ',') }} جنيه</div>
                            <div class="calc-formula">{{ $savedMonthlyHours }} × {{ $savedHourlyRate }}</div>
                        </div>
                        <div class="calc-item">
                            <div class="calc-label">قيمة اليوم الواحد</div>
                            <div class="calc-value">{{ number_format($savedDailySalary, 0, '.', ',') }} جنيه</div>
                <div class="calc-formula">{{ number_format($savedFullSalary, 0, '.', ',') }} ÷ {{ $savedMonthlyDays }}</div>
                        </div>
                        <div class="calc-item calc-result">
                            <div class="calc-label">المستحقات</div>
                            <div class="calc-value">{{ number_format(round($savedEntitlementsBySalary), 0, '.', ',') }} جنيه</div>
                <div class="calc-formula">{{ number_format($savedDailySalary, 0, '.', ',') }} × {{ $savedDaysWorked }}</div>
                        </div>
                        <div class="calc-item calc-result">
                            <div class="calc-label">الراتب الصافي</div>
                            <div class="calc-value">{{ number_format(round($savedNetSalaryBySalary), 0, '.', ',') }} جنيه</div>
                <div class="calc-formula">بعد خصم السلف ({{ number_format($savedTotalAdvances, 0, '.', ',') }} جنيه)</div>
                        </div>
                    </div>
                </div>
            </div>
-->
            <!-- Custom Fields -->
            @if($employee->customFieldValues->count() > 0)
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-cogs"></i> الحقول المخصصة
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        @foreach($employee->customFieldValues as $fieldValue)
                        <div class="info-item">
                            <div class="info-label">{{ $fieldValue->customField->name }}</div>
                            <div class="info-value">{{ $fieldValue->value }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Employee Advances -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-hand-holding-usd"></i> سلف الموظف ({{ $employee->advances->count() }})
                </div>
                <div class="section-content">
                    @if($employee->advances->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المبلغ</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    <th>الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->advances as $advance)
                                <tr>
                                    <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                    <td>{{ number_format($advance->amount, 0, '.', ',') }} جنيه</td>
                            <td>{{ number_format($advance->paid_amount, 0, '.', ',') }} جنيه</td>
                            <td>{{ number_format($advance->amount - $advance->paid_amount, 0, '.', ',') }} جنيه</td>
                                    <td>
                                        @if($advance->status == 'pending')
                                            <span class="badge badge-warning">معلق</span>
                                        @elseif($advance->status == 'approved')
                                            <span class="badge badge-success">موافق عليه</span>
                                        @elseif($advance->status == 'paid')
                                            <span class="badge badge-info">مدفوع</span>
                                        @else
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif
                                    </td>
                                    <td>{{ $advance->notes ?? 'لا توجد ملاحظات' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">
                            <i class="fas fa-info-circle"></i> لا توجد سلف مسجلة لهذا الموظف
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Documents -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-file-alt"></i> مستندات الموظف ({{ $employee->documents->count() }})
                </div>
                <div class="section-content">
                    @if($employee->documents->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>نوع المستند</th>
                                    <th>الوصف</th>
                                    <th>تاريخ الرفع</th>
                                    <th>الحالة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->documents as $document)
                                <tr>
                                    <td>{{ $document->document_type }}</td>
                                    <td>{{ $document->description ?? 'لا يوجد وصف' }}</td>
                                    <td>{{ $document->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($document->is_verified)
                                            <span class="badge badge-success">موثق</span>
                                        @else
                                            <span class="badge badge-warning">غير موثق</span>
                                        @endif
                                    </td>
                                    <td>{{ $document->notes ?? 'لا توجد ملاحظات' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">
                            <i class="fas fa-info-circle"></i> لا توجد مستندات مرفوعة لهذا الموظف
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employee Tools -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-tools"></i> الأدوات المخصصة ({{ $employee->assignedTools->count() }})
                </div>
                <div class="section-content">
                    @if($employee->assignedTools->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>اسم الأداة</th>
                                    <th>الرقم التسلسلي</th>
                                    <th>تاريخ التخصيص</th>
                                    <th>تاريخ الإرجاع</th>
                                    <th>الحالة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->assignedTools as $tool)
                                <tr>
                                    <td>{{ $tool->name }}</td>
                                    <td>{{ $tool->serial_number ?? 'غير محدد' }}</td>
                                    <td>{{ $tool->pivot->assigned_at ? \Carbon\Carbon::parse($tool->pivot->assigned_at)->format('Y-m-d') : 'غير محدد' }}</td>
                                    <td>{{ $tool->pivot->returned_at ? \Carbon\Carbon::parse($tool->pivot->returned_at)->format('Y-m-d') : 'لم يتم الإرجاع' }}</td>
                                    <td>
                                        @if($tool->pivot->returned_at)
                                            <span class="badge badge-secondary">مُرجع</span>
                                        @else
                                            <span class="badge badge-success">مُخصص</span>
                                        @endif
                                    </td>
                                    <td>{{ $tool->pivot->notes ?? 'لا توجد ملاحظات' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">
                            <i class="fas fa-info-circle"></i> لا توجد أدوات مخصصة لهذا الموظف
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Print Footer -->
        <div class="print-footer">
            <div class="footer-grid">
                <div class="footer-item">
                    <div class="footer-label">اسم الشركة</div>
                    <div class="footer-value">{{ config('app.name', 'نظام إدارة الموظفين') }}</div>
                </div>
                <div class="footer-item">
                    <div class="footer-label">تاريخ التقرير</div>
                    <div class="footer-value">{{ date('Y-m-d H:i:s') }}</div>
                </div>
                <div class="footer-item">
                    <div class="footer-label">رقم الموظف</div>
                    <div class="footer-value">#{{ $employee->id }}</div>
                </div>
            </div>
            <hr style="margin: 20px 0; border-color: rgba(255,255,255,0.3);">
            <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">
                <i class="fas fa-shield-alt"></i> هذا التقرير تم إنشاؤه تلقائياً من نظام إدارة الموظفين - جميع الحقوق محفوظة
            </p>
        </div>
    </div>

    <script>
        // Auto-focus on print when page loads
        window.addEventListener('load', function() {
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+P for print
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
                // Escape to go back
                if (e.key === 'Escape') {
                    window.history.back();
                }
            });
        });
    </script>
</body>
</html>