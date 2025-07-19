<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAdvance;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\CustomField;
use Illuminate\Http\Request;

class EmployeeAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeAdvance::with('employee');

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by employee name with Arabic character normalization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  // Arabic character variations
                  ->orWhere('name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ؤ', 'و', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ئ', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ة', 'ه', $search) . "%");
            });
        }

        $advances = $query->latest()->get();
        return view('employee-advances.index', compact('advances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::all();
        $selectedEmployeeId = $request->get('employee_id');
        $customFields = CustomField::where('applies_to', 'advance')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return view('employee-advances.create', compact('employees', 'selectedEmployeeId', 'customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'request_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,paid',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['remaining_amount'] = $data['amount']; // Set remaining amount to full amount initially

        $employeeAdvance = EmployeeAdvance::create($data);

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'advance')
            ->where('is_active', true)
            ->get();

        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            if ($request->has($fieldName)) {
                $employeeAdvance->customFieldValues()->create([
                    'custom_field_id' => $field->id,
                    'value' => $request->input($fieldName)
                ]);
            }
        }

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم إضافة طلب السلفة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeAdvance $employeeAdvance)
    {
        $employeeAdvance->load('employee');
        return view('employee-advances.show', compact('employeeAdvance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeAdvance $employeeAdvance)
    {
        $employees = Employee::all();
        $customFields = CustomField::where('applies_to', 'advance')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $employeeAdvance->load('customFieldValues');
        return view('employee-advances.edit', compact('employeeAdvance', 'employees', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeAdvance $employeeAdvance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'request_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,paid',
            'notes' => 'nullable|string',
        ]);

        $employeeAdvance->update($request->all());

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'advance')
            ->where('is_active', true)
            ->get();

        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            $existingValue = $employeeAdvance->customFieldValues()
                ->where('custom_field_id', $field->id)
                ->first();

            if ($request->has($fieldName)) {
                if ($existingValue) {
                    $existingValue->update(['value' => $request->input($fieldName)]);
                } else {
                    $employeeAdvance->customFieldValues()->create([
                        'custom_field_id' => $field->id,
                        'value' => $request->input($fieldName)
                    ]);
                }
            } elseif ($existingValue) {
                $existingValue->delete();
            }
        }

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم تحديث طلب السلفة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeAdvance $employeeAdvance)
    {
        $employeeAdvance->delete();

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم حذف طلب السلفة بنجاح');
    }

    /**
     * Approve advance request
     */
    public function approve(EmployeeAdvance $employeeAdvance)
    {
        $employeeAdvance->update(['status' => 'approved']);

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم الموافقة على طلب السلفة');
    }

    /**
     * Reject advance request
     */
    public function reject(EmployeeAdvance $employeeAdvance)
    {
        $employeeAdvance->update(['status' => 'rejected']);

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم رفض طلب السلفة');
    }

    /**
     * Mark advance as paid
     */
    public function markAsPaid(EmployeeAdvance $employeeAdvance)
    {
        $employeeAdvance->update(['status' => 'paid']);

        return redirect()->route('employee-advances.index')
            ->with('success', 'تم تسديد السلفة');
    }
}
