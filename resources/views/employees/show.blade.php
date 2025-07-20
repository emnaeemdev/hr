@extends('layouts.app')

@section('content')
<!-- Print Header - Only visible when printing -->
<div class="print-only print-header">
    <h1>تقرير تفاصيل الموظف</h1>
    <p>تاريخ الطباعة: {{ date('Y-m-d H:i:s') }}</p>
</div>



<style>
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    
    body {
        font-family: 'Arial', sans-serif !important;
        font-size: 11px !important;
        line-height: 1.4 !important;
        margin: 0 !important;
        padding: 15px !important;
        background: white !important;
    }
    
    /* Hide sidebar and navigation during print */
    .sidebar, .col-md-3, .col-lg-2 {
        display: none !important;
    }
    
    .main-content, .col-md-9, .col-lg-10 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
    
    .navbar {
        display: none !important;
    }
    
    .container-fluid {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .btn, .card-header .btn, .no-print {
        display: none !important;
    }
    
    .card {
        border: 2px solid #2c3e50 !important;
        border-radius: 8px !important;
        margin-bottom: 25px !important;
        page-break-inside: avoid;
        box-shadow: none !important;
        background: white !important;
    }
    
    .card-header {
        background: linear-gradient(135deg, #3498db, #2980b9) !important;
        color: white !important;
        border-bottom: 2px solid #2c3e50 !important;
        padding: 12px 20px !important;
        border-radius: 6px 6px 0 0 !important;
        text-align: center !important;
    }
    
    .card-header h4, .card-header h5 {
        color: white !important;
        margin: 0 !important;
        font-weight: bold !important;
        font-size: 14px !important;
    }
    
    .card-body {
        padding: 20px !important;
        background: #fafafa !important;
    }
    
    .table {
        border-collapse: collapse !important;
        width: 100% !important;
        margin-bottom: 15px !important;
        background: white !important;
    }
    
    .table th {
        background: #34495e !important;
        color: white !important;
        border: 1px solid #2c3e50 !important;
        padding: 10px 8px !important;
        font-weight: bold !important;
        text-align: center !important;
        font-size: 10px !important;
    }
    
    .table td {
        border: 1px solid #bdc3c7 !important;
        padding: 8px !important;
        background: white !important;
        font-size: 10px !important;
    }
    
    .table-borderless th, .table-borderless td {
        border: none !important;
        padding: 6px 8px !important;
    }
    
    .table-borderless th {
        background: #ecf0f1 !important;
        color: #2c3e50 !important;
        font-weight: bold !important;
        width: 35% !important;
    }
    
    .alert {
        border: 2px solid #3498db !important;
        background: #ebf3fd !important;
        border-radius: 6px !important;
        padding: 15px !important;
        margin: 15px 0 !important;
    }
    
    .alert h6 {
        color: #2c3e50 !important;
        font-weight: bold !important;
        margin-bottom: 10px !important;
        font-size: 12px !important;
    }
    
    .text-success {
        color: #27ae60 !important;
        font-weight: bold !important;
    }
    
    .text-primary {
        color: #3498db !important;
        font-weight: bold !important;
    }
    
    .text-warning {
        color: #f39c12 !important;
        font-weight: bold !important;
    }
    
    .text-danger {
        color: #e74c3c !important;
        font-weight: bold !important;
    }
    
    .fw-bold {
        font-weight: bold !important;
    }
    
    .badge {
        border: 1px solid #2c3e50 !important;
        padding: 3px 8px !important;
        border-radius: 4px !important;
        font-size: 9px !important;
        font-weight: bold !important;
    }
    
    .bg-success {
        background: #27ae60 !important;
        color: white !important;
    }
    
    .bg-warning {
        background: #f39c12 !important;
        color: white !important;
    }
    
    .bg-danger {
        background: #e74c3c !important;
        color: white !important;
    }
    
    .bg-info {
        background: #3498db !important;
        color: white !important;
    }
    
    .bg-secondary {
        background: #95a5a6 !important;
        color: white !important;
    }
    
    h4, h5, h6 {
        color: #2c3e50 !important;
        font-weight: bold !important;
    }
    
    .row {
        margin: 0 !important;
    }
    
    .col-md-3, .col-md-4, .col-md-6, .col-md-12 {
        padding: 5px !important;
    }
    
    hr {
        border-top: 2px solid #bdc3c7 !important;
        margin: 15px 0 !important;
    }
    
    .text-muted {
        color: #7f8c8d !important;
        font-size: 9px !important;
    }
    
    /* Print Header */
    .print-header {
        text-align: center !important;
        margin-bottom: 30px !important;
        padding: 20px !important;
        border: 3px solid #2c3e50 !important;
        background: linear-gradient(135deg, #ecf0f1, #bdc3c7) !important;
        border-radius: 10px !important;
    }
    
    .print-header h1 {
        color: #2c3e50 !important;
        font-size: 18px !important;
        margin: 0 !important;
        font-weight: bold !important;
    }
    
    .print-header p {
        color: #34495e !important;
        font-size: 12px !important;
        margin: 5px 0 0 0 !important;
    }
    
    /* Page break control */
    .page-break {
        page-break-before: always !important;
    }
    
    .avoid-break {
        page-break-inside: avoid !important;
    }
}

/* Screen styles for print buttons */
.print-btn-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.print-btn {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    transition: all 0.3s ease;
    cursor: pointer;
}

.print-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
    background: linear-gradient(135deg, #2980b9, #3498db);
}

@media screen {
    .print-only {
        display: none;
    }
}

@media print {
    .print-only {
        display: block !important;
    }
    
    .screen-only {
        display: none !important;
    }
    
    /* Show entitlements calculator details in print */
    .entitlements-print-details {
        display: block !important;
        margin-bottom: 20px !important;
        padding: 15px !important;
        border: 2px solid #3498db !important;
        background: #f8f9fa !important;
        border-radius: 8px !important;
    }
    
    .entitlements-print-details h6 {
        color: #2c3e50 !important;
        font-weight: bold !important;
        margin-bottom: 15px !important;
        text-align: center !important;
        font-size: 14px !important;
    }
    
    .entitlements-input-row {
        display: flex !important;
        justify-content: space-between !important;
        margin-bottom: 10px !important;
    }
    
    .entitlements-input-item {
        flex: 1 !important;
        margin: 0 5px !important;
        text-align: center !important;
    }
    
    .entitlements-input-item strong {
        display: block !important;
        color: #34495e !important;
        font-size: 10px !important;
        margin-bottom: 5px !important;
    }
    
    .entitlements-input-item span {
        display: block !important;
        color: #2980b9 !important;
        font-weight: bold !important;
        font-size: 12px !important;
        padding: 5px !important;
        border: 1px solid #bdc3c7 !important;
        background: white !important;
        border-radius: 4px !important;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    // Hide screen-only elements and show print-only elements
    const screenElements = document.querySelectorAll('.screen-only');
    const printElements = document.querySelectorAll('.print-only');
    
    screenElements.forEach(el => el.style.display = 'none');
    printElements.forEach(el => el.style.display = 'block');
    
    // Get only the main content container instead of the whole body
    const element = document.querySelector('.container-fluid') || document.body;
    const employeeName = '{{ $employee->name }}';
    const currentDate = new Date().toISOString().slice(0, 10);
    
    const opt = {
        margin: [0.5, 0.5, 0.5, 0.5],
        filename: `تقرير_الموظف_${employeeName}_${currentDate}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2,
            useCORS: true,
            letterRendering: true,
            allowTaint: true,
            ignoreElements: function(element) {
                // Ignore sidebar and navigation elements
                return element.classList.contains('sidebar') || 
                       element.classList.contains('navbar') ||
                       element.classList.contains('nav') ||
                       element.tagName === 'NAV';
            }
        },
        jsPDF: { 
            unit: 'in', 
            format: 'a4', 
            orientation: 'portrait',
            compress: true
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };
    
    html2pdf().set(opt).from(element).save().then(() => {
        // Restore original display states
        screenElements.forEach(el => el.style.display = '');
        printElements.forEach(el => el.style.display = '');
    });
}

// Enhanced print function
function printReport() {
    window.print();
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+P for print
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printReport();
    }
    // Ctrl+D for download PDF
    if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        downloadPDF();
    }
});

// Save entitlements function
const saveEntitlementsForm = document.getElementById('save-entitlements-form');
if (saveEntitlementsForm) {
    saveEntitlementsForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const monthlyHoursEl = document.getElementById('monthly_hours');
    const hourlyRateEl = document.getElementById('hourly_rate');
    const daysWorkedEl = document.getElementById('days_worked');
    const monthlyDaysEl = document.getElementById('monthly_days');
    
    if (!monthlyHoursEl || !hourlyRateEl || !daysWorkedEl || !monthlyDaysEl) {
        console.warn('Some input elements not found, skipping calculation');
        return;
    }
    
    const monthlyHours = parseFloat(monthlyHoursEl.value) || 0;
    const hourlyRate = parseFloat(hourlyRateEl.value) || 0;
    const daysWorked = parseFloat(daysWorkedEl.value) || 0;
    const monthlyDays = parseFloat(monthlyDaysEl.value) || 0;
    const notes = '';
    
    const data = {
        monthly_hours: monthlyHours,
        hourly_rate: hourlyRate,
        days_worked: daysWorked,
        monthly_days: monthlyDays,
        notes: notes,
        _token: '{{ csrf_token() }}'
    };
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("employees.save-entitlements", $employee) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('تم حفظ نتائج المستحقات بنجاح!');
            // Notes removed
        } else {
            alert('حدث خطأ أثناء حفظ النتائج: ' + (result.message || 'خطأ غير معروف'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حفظ النتائج');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
    });
}

// Auto-save function for input changes
function autoSaveEntitlements() {
    // Add visual feedback
    const netSalaryDisplay = document.getElementById('net-salary-display');
    if (netSalaryDisplay) {
        netSalaryDisplay.style.backgroundColor = '#fff3cd';
        netSalaryDisplay.style.transition = 'background-color 0.3s';
    }
    
    const monthlyHoursEl = document.getElementById('monthly_hours');
    const hourlyRateEl = document.getElementById('hourly_rate');
    const daysWorkedEl = document.getElementById('days_worked');
    const monthlyDaysEl = document.getElementById('monthly_days');
    
    if (!monthlyHoursEl || !hourlyRateEl || !daysWorkedEl || !monthlyDaysEl) {
        console.warn('Some input elements not found in autoSaveEntitlements, skipping calculation');
        return;
    }
    
    const monthlyHours = parseFloat(monthlyHoursEl.value) || 0;
    const hourlyRate = parseFloat(hourlyRateEl.value) || 0;
    const daysWorked = parseFloat(daysWorkedEl.value) || 0;
    const monthlyDays = parseFloat(monthlyDaysEl.value) || 0;
    
    // Calculate values
    const dailyHours = monthlyDays > 0 ? monthlyHours / monthlyDays : 0;
    const actualHours = daysWorked * dailyHours;
    const entitlementsByHours = actualHours * hourlyRate;
    
    const fullSalary = monthlyHours * hourlyRate;
    const dailySalary = monthlyDays > 0 ? fullSalary / monthlyDays : 0;
    const entitlementsBySalary = daysWorked * dailySalary;
    
    // Get total advances from the page
    const totalAdvancesText = document.querySelector('td.text-danger').textContent;
    const totalAdvances = parseFloat(totalAdvancesText.replace(/[^\d.-]/g, '')) || 0;
    
    const netSalaryByHours = Math.max(0, entitlementsByHours - totalAdvances);
    const netSalaryBySalary = Math.max(0, entitlementsBySalary - totalAdvances);
    
    // Update the net salary display in employee details
if (netSalaryDisplay) {
    netSalaryDisplay.innerHTML = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(Math.ceil(netSalaryBySalary)) + ' جنيه';
}
    
    // Update monthly salary display
    const thElements = document.querySelectorAll('th');
    let monthlySalaryRow = null;
    thElements.forEach(th => {
        if (th.textContent.includes('الراتب الشهري:')) {
            monthlySalaryRow = th.parentElement;
        }
    });
    if (monthlySalaryRow) {
        const monthlySalaryCell = monthlySalaryRow.querySelector('td');
        if (monthlySalaryCell) {
            monthlySalaryCell.innerHTML = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.ceil(fullSalary)) + ' جنيه <small class="text-muted">(' + monthlyHours + ' ساعة × ' + hourlyRate + ' جنيه)</small>';
        }
    }
    
    // Update calculator results
    updateCalculatorResults({
        dailyHours,
        actualHours,
        entitlementsByHours,
        fullSalary,
        dailySalary,
        entitlementsBySalary,
        netSalaryByHours,
        netSalaryBySalary,
        totalAdvances,
        monthlyHours,
        hourlyRate,
        daysWorked,
        monthlyDays
    });
    
    // Send to server
    const data = {
        monthly_hours: monthlyHours,
        hourly_rate: hourlyRate,
        days_worked: daysWorked,
        monthly_days: monthlyDays,
        daily_hours: dailyHours,
        actual_hours: actualHours,
        entitlements_by_hours: entitlementsByHours,
        full_salary: fullSalary,
        daily_salary: dailySalary,
        entitlements_by_salary: entitlementsBySalary,
        net_salary_by_hours: netSalaryByHours,
        net_salary_by_salary: netSalaryBySalary,
        total_advances: totalAdvances,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('/api/employees/{{ $employee->id }}/entitlements', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    }).then(() => {
        // Reset background color after successful update
        setTimeout(() => {
            if (netSalaryDisplay) {
                netSalaryDisplay.style.backgroundColor = '';
            }
        }, 1000);
    }).catch(error => {
        console.error('Error saving entitlements:', error);
        // Reset background color on error too
        if (netSalaryDisplay) {
            netSalaryDisplay.style.backgroundColor = '#f8d7da';
            setTimeout(() => {
                netSalaryDisplay.style.backgroundColor = '';
            }, 2000);
        }
    });
}

function updateCalculatorResults(values) {
    // Update first method results
    const dailyHoursSpan = document.querySelector('.text-primary');
    if (dailyHoursSpan && dailyHoursSpan.textContent.includes('ساعة')) {
        dailyHoursSpan.textContent = Math.round(values.dailyHours) + ' ساعة';
    }
    
    // Update all calculator display values
    const calculatorSection = document.querySelector('.alert-info');
    if (calculatorSection) {
        // Update daily hours
        const dailyHoursElements = calculatorSection.querySelectorAll('.text-primary');
        if (dailyHoursElements[0]) {
            dailyHoursElements[0].textContent = Math.round(values.dailyHours) + ' ساعة';
        }
        if (dailyHoursElements[1]) {
            dailyHoursElements[1].textContent = Math.round(values.actualHours) + ' ساعة';
        }
        
        // Update entitlements and net salary displays
        const successElements = calculatorSection.querySelectorAll('.text-success.fw-bold');
        if (successElements[0]) {
            successElements[0].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(values.entitlementsByHours)) + ' جنيه';
        }
        if (successElements[1]) {
            successElements[1].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(Math.max(0, values.entitlementsBySalary - values.totalAdvances))) + ' جنيه';
        }
        
        const infoElements = calculatorSection.querySelectorAll('.text-info.fw-bold');
        if (infoElements[0]) {
            infoElements[0].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(values.netSalaryByHours)) + ' جنيه';
        }
        if (infoElements[1]) {
            infoElements[1].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(values.netSalaryBySalary)) + ' جنيه';
        }
        
        // Update warning elements (full salary and daily salary)
        const warningElements = calculatorSection.querySelectorAll('.text-warning');
        if (warningElements[0]) {
            warningElements[0].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(values.fullSalary)) + ' جنيه';
        }
        if (warningElements[1]) {
            warningElements[1].textContent = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(values.dailySalary)) + ' جنيه';
        }
    }
    
    // Update the net salary display outside the calculator
    const netSalaryDisplay = document.getElementById('net-salary-display');
    if (netSalaryDisplay) {
        netSalaryDisplay.textContent = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(Math.round(Math.max(0, values.entitlementsBySalary - values.totalAdvances))) + ' جنيه';
    }
    
    // Update the entitlements by salary display in the calculator
    const entitlementsBySalaryDisplay = document.getElementById('entitlements-by-salary-display');
    if (entitlementsBySalaryDisplay) {
        entitlementsBySalaryDisplay.textContent = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(Math.round(Math.max(0, values.entitlementsBySalary - values.totalAdvances))) + ' جنيه';
    }
}

// Add event listeners for auto-update and page reload
function initializeEventListeners() {
    try {
        const inputs = ['monthly_hours', 'hourly_rate', 'days_worked', 'monthly_days'];
        let foundInputs = 0;
        
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                foundInputs++;
                // Add multiple event types for better responsiveness
                input.addEventListener('input', autoSaveEntitlements);
                input.addEventListener('change', handleInputChange);
                input.addEventListener('keyup', autoSaveEntitlements);
                input.addEventListener('blur', handleInputChange);
            } else {
                console.warn('Input element not found:', inputId);
            }
        });
        
        if (foundInputs === inputs.length) {
            console.log('All input elements found and listeners added');
            // Call once on page load to ensure initial calculation
            setTimeout(autoSaveEntitlements, 200);
        } else {
            console.warn('Not all input elements found, retrying in 500ms');
            setTimeout(initializeEventListeners, 500);
        }
    } catch (error) {
        console.error('Error initializing event listeners:', error);
        setTimeout(initializeEventListeners, 1000);
    }
}

// Handle input change with page reload
function handleInputChange() {
    // Save the entitlements first
    autoSaveEntitlements();
    
    // Show loading message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-info alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-sync-alt fa-spin"></i> جاري تحديث البيانات...
    `;
    
    const entitlementsSection = document.getElementById('entitlements-section');
    if (entitlementsSection) {
        entitlementsSection.parentNode.insertBefore(alertDiv, entitlementsSection);
    }
    
    // Reload the page after a short delay to allow saving
    setTimeout(() => {
        window.location.reload();
    }, 1500);
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initializeEventListeners, 100);
});

// Also add a fallback for when the page is fully loaded
window.addEventListener('load', function() {
    setTimeout(initializeEventListeners, 200);
});





// Show error message function
function showErrorMessage(error) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle"></i> ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const entitlementsSection = document.getElementById('entitlements-section');
        entitlementsSection.parentNode.insertBefore(alertDiv, entitlementsSection);
        
        // Auto-hide alert after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
}


</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Employee Details Card -->
            <div class="card mb-4 avoid-break">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل الموظف: {{ $employee->name }}</h4>
                    <div class="screen-only">
                        <a href="{{ route('employees.print', $employee->id) }}" class="btn btn-success btn-sm me-2" target="_blank">
                            <i class="fas fa-print"></i> صفحة الطباعة
                        </a>
                        <button onclick="window.print()" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-print"></i> طباعة سريعة
                        </button>
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
                                    <th width="30%">الوظيفة:</th>
                                    <td>{{ $employee->position ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التوظيف:</th>
                                    <td>{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>

                                <tr>
                                    <th>الراتب الشهري:</th>
                                    <td>
                                        @php
                                            $monthlyHours = (float) request('monthly_hours', 208);
                                            $hourlyRate = (float) request('hourly_rate', 36.06);
                                            $fullSalary = $monthlyHours * $hourlyRate;
                                        @endphp
                                        {{ number_format(round($fullSalary), 0, '.', ',') }} جنيه
                                        <small class="text-muted">({{ number_format($monthlyHours, 0, '.', ',') }} ساعة × {{ number_format($hourlyRate, 0, '.', ',') }} جنيه)</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>إجمالي السلف:</th>
                                    <td class="text-danger">{{ number_format($employee->total_remaining_advances, 0, '.', ',') }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>الراتب الصافي:</th>
                                    <td class="text-success fw-bold" id="net-salary-display">
                                        @php
                                            $latestEntitlement = $employee->latestEntitlement;
                                            $defaultMonthlyHours = $latestEntitlement ? $latestEntitlement->monthly_hours : 208;
                                            $defaultHourlyRate = $latestEntitlement ? $latestEntitlement->hourly_rate : 36.06;
                                            $defaultDaysWorked = $latestEntitlement ? $latestEntitlement->days_worked : 26;
                                            $defaultMonthlyDays = $latestEntitlement ? $latestEntitlement->monthly_days : 26;
                                            
                                            $monthlyHours = (float) request('monthly_hours', $defaultMonthlyHours);
                                            $hourlyRate = (float) request('hourly_rate', $defaultHourlyRate);
                                            $daysWorked = (float) request('days_worked', $defaultDaysWorked);
                                            $monthlyDays = (float) request('monthly_days', $defaultMonthlyDays);
                                            
                                            $fullSalary = $monthlyHours * $hourlyRate;
                                            $dailySalary = $monthlyDays > 0 ? $fullSalary / $monthlyDays : 0;
                                            $entitlementsBySalary = $daysWorked * $dailySalary;
                                            $totalAdvances = $employee->advances->where('status', '!=', 'rejected')->sum('remaining_amount');
                                            $netSalary = max(0, $entitlementsBySalary - $totalAdvances);
                                        @endphp
                                              {{ number_format(round($netSalary), 0, '.', ',') }} جنيه

                                    </td>
                                    
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

            <!-- Entitlements Calculator Print Details - Only visible when printing -->
            <div class="entitlements-print-details print-only">
                <h6><i class="fas fa-calculator"></i> تفاصيل حاسبة المستحقات</h6>
                <div class="entitlements-input-row">
                    <div class="entitlements-input-item">
                        <strong>عدد ساعات الشهر</strong>
                        <span>{{ request('monthly_hours', $latestEntitlement ? $latestEntitlement->monthly_hours : 208) }}</span>
                    </div>
                    <div class="entitlements-input-item">
                        <strong>قيمة الساعة (جنيه)</strong>
                        <span>{{ request('hourly_rate', $latestEntitlement ? $latestEntitlement->hourly_rate : 36.06) }}</span>
                    </div>
                    <div class="entitlements-input-item">
                        <strong>عدد الأيام المشتغلة</strong>
                        <span>{{ request('days_worked', $latestEntitlement ? $latestEntitlement->days_worked : 26) }}</span>
                    </div>
                    <div class="entitlements-input-item">
                        <strong>عدد أيام الشهر الكاملة</strong>
                        <span>{{ request('monthly_days', $latestEntitlement ? $latestEntitlement->monthly_days : 26) }}</span>
                    </div>
                </div>
            </div>

            <!-- Entitlements Calculator -->
            <div class="card mb-4 avoid-break screen-only" id="entitlements-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> حاسبة المستحقات</h5>
                    </div>
                <div class="card-body">
                    @php
                        // Get latest saved entitlement values or use defaults
                        $latestEntitlement = $employee->latestEntitlement;
                        $defaultMonthlyHours = $latestEntitlement ? $latestEntitlement->monthly_hours : 208;
                        $defaultHourlyRate = $latestEntitlement ? $latestEntitlement->hourly_rate : 36.06;
                        $defaultDaysWorked = $latestEntitlement ? $latestEntitlement->days_worked : 26;
                        $defaultMonthlyDays = $latestEntitlement ? $latestEntitlement->monthly_days : 26;
                        $savedNotes = $latestEntitlement ? $latestEntitlement->notes : '';
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="monthly_hours" class="form-label">عدد ساعات الشهر الكاملة</label>
                                <input type="number" class="form-control" id="monthly_hours" name="monthly_hours" 
                                       value="{{ request('monthly_hours', $defaultMonthlyHours) }}" 
                                       min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">قيمة الساعة (جنيه)</label>
                                <input type="number" step="0.01" class="form-control" id="hourly_rate" name="hourly_rate" 
                                       value="{{ request('hourly_rate', $defaultHourlyRate) }}" 
                                       min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="days_worked" class="form-label">عدد الأيام المشتغلة</label>
                                <input type="number" class="form-control" id="days_worked" name="days_worked" 
                                       value="{{ request('days_worked', $defaultDaysWorked) }}" 
                                       min="1" max="31">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                    <label for="monthly_days" class="form-label">عدد أيام الشهر الكاملة</label>
                                    <input type="number" class="form-control" id="monthly_days" name="monthly_days" 
                                           value="{{ request('monthly_days', $defaultMonthlyDays) }}" 
                                           min="1" max="31">
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @php
                        
                        $monthlyHours = (float) request('monthly_hours', $defaultMonthlyHours);
                        $hourlyRate = (float) request('hourly_rate', $defaultHourlyRate);
                        $daysWorked = (float) request('days_worked', $defaultDaysWorked);
                        $monthlyDays = (float) request('monthly_days', $defaultMonthlyDays);
                        
                        // الطريقة الأولى: حساب بعدد الساعات
                        $dailyHours = $monthlyDays > 0 ? $monthlyHours / $monthlyDays : 0;
                        $actualHours = $daysWorked * $dailyHours;
                        $entitlementsByHours = $actualHours * $hourlyRate;
                        
                        // الطريقة الثانية: حساب بالراتب الكامل
                        $fullSalary = $monthlyHours * $hourlyRate;
                        $dailySalary = $monthlyDays > 0 ? $fullSalary / $monthlyDays : 0;
                        $entitlementsBySalary = $daysWorked * $dailySalary;
                        
                        // حساب إجمالي السلف المتبقية
                        $totalAdvances = $employee->advances->where('status', '!=', 'rejected')->sum('remaining_amount');
                        
                        // حساب الراتب الصافي (بعد خصم السلف)
                        $netSalaryByHours = max(0, $entitlementsByHours - $totalAdvances);
                        $netSalaryBySalary = max(0, $entitlementsBySalary - $totalAdvances);
                    @endphp
                    
                  <!-- نتائج الحساب -->
<div class="alert alert-info">
    <h6><i class="fas fa-calculator"></i> نتائج الحساب:</h6>
    <div class="row">
        <!-- العمود الأيسر -->
        <div class="col-md-6">
            <div class="mb-2">
                <strong>ساعات اليوم الواحد:</strong>
                <span class="text-primary fw-bold">{{ number_format($dailyHours, 0, '.', ',') }} ساعة</span>
            </div>
            <div class="mb-2">
                <strong>إجمالي الساعات الفعلية:</strong>
                <span class="text-primary fw-bold">{{ number_format($actualHours, 0, '.', ',') }} ساعة</span>
            </div>
            <div class="mb-2">
                <strong>راتب اليوم الواحد:</strong>
                <span class="text-primary fw-bold">{{ number_format($dailySalary, 0, '.', ',') }} جنيه</span>
            </div>
        </div>

        <!-- العمود الأيمن -->
        <div class="col-md-6">
            <div class="mb-2">
                <strong>الراتب الشهري الكامل:</strong>
                <span class="text-primary fw-bold">{{ number_format(round($fullSalary), 0, '.', ',') }} جنيه</span>
            </div>
            <div class="mb-2">
                <strong>إجمالي السلف المتبقية:</strong>
                <span class="text-danger fw-bold">{{ number_format($totalAdvances, 0, '.', ',') }} جنيه</span>
            </div>
            <div class="mb-2">
                <strong>المستحقات (بالراتب):</strong>
                <span class="text-success fw-bold" id="entitlements-by-salary-display">{{ number_format(round(max(0, $entitlementsBySalary - $totalAdvances)), 0, '.', ',') }} جنيه</span>
            </div>
        </div>
    </div>
</div>

                    <!-- Save Entitlements Section -->
                    <div class="row mt-3 screen-only">
                        <div class="col-md-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-save"></i> حفظ نتائج حاسبة المستحقات</h6>
                                </div>
                                <div class="card-body">
                                    <form id="save-entitlements-form">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> حفظ النتائج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Advances -->
            <div class="card mb-4 avoid-break">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">السلف</h5>
                    <div class="screen-only">
                        <a href="{{ route('employee-advances.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة سلفة
                        </a>
                    </div>
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
                                            <td>{{ number_format($advance->amount, 0, '.', ',') }} جنيه</td>
                    <td>{{ number_format($advance->remaining_amount, 0, '.', ',') }} جنيه</td>
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
            <div class="card mb-4 avoid-break">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">المستندات</h5>
                    <div class="screen-only">
                        <a href="{{ route('employee-documents.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة مستند
                        </a>
                    </div>
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
            <div class="card mb-4 avoid-break">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الأدوات المخصصة</h5>
                    <div class="screen-only">
                        <a href="{{ route('tools.create') }}?employee_id={{ $employee->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> تخصيص أداة
                        </a>
                    </div>
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

<!-- Print Footer - Only visible when printing -->
<div class="print-only" style="margin-top: 30px; padding: 20px; border-top: 2px solid #2c3e50; text-align: center; background: #ecf0f1;">
    <div class="row">
        <div class="col-md-4">
            <strong>اسم الشركة:</strong><br>
            <span class="text-muted">{{ config('app.name', 'نظام إدارة الموظفين') }}</span>
        </div>
        <div class="col-md-4">
            <strong>تاريخ التقرير:</strong><br>
            <span class="text-muted">{{ date('Y-m-d H:i:s') }}</span>
        </div>
        <div class="col-md-4">
            <strong>رقم الصفحة:</strong><br>
            <span class="text-muted">صفحة 1 من 1</span>
        </div>
    </div>
    <hr style="margin: 15px 0;">
    <p class="text-muted" style="margin: 0; font-size: 10px;">
        هذا التقرير تم إنشاؤه تلقائياً من نظام إدارة الموظفين - جميع الحقوق محفوظة
    </p>
</div>
@endsection