<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\CustomField;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['branch', 'tools', 'documents']);

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by advances
        if ($request->filled('has_advances')) {
            if ($request->has_advances == '1') {
                $query->whereHas('advances');
            } elseif ($request->has_advances == '0') {
                $query->whereDoesntHave('advances');
            }
        }

        // Filter by tools
        if ($request->filled('has_tools')) {
            if ($request->has_tools == '1') {
                $query->whereHas('tools');
            } elseif ($request->has_tools == '0') {
                $query->whereDoesntHave('tools');
            }
        }

        // Filter by documents
        if ($request->filled('has_documents')) {
            if ($request->has_documents == '1') {
                $query->whereHas('documents');
            } elseif ($request->has_documents == '0') {
                $query->whereDoesntHave('documents');
            }
        }

        // Search by name with Arabic character normalization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  // Arabic character variations
                  ->orWhere('name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ؤ', 'و', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ئ', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ة', 'ه', $search) . "%");
            });
        }

        $employees = $query->latest()->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $customFields = CustomField::where('applies_to', 'employee')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return view('employees.create', compact('branches', 'customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::create($request->all());

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'employee')
            ->where('is_active', true)
            ->get();

        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                if ($value !== null && $value !== '') {
                    $employee->customFieldValues()->create([
                        'custom_field_id' => $field->id,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('branch', 'advances', 'documents', 'tools', 'customFieldValues.customField');
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $branches = Branch::all();
        $customFields = CustomField::where('applies_to', 'employee')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $employee->load('customFieldValues');
        return view('employees.edit', compact('employee', 'branches', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        $employee->update($request->all());

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'employee')
            ->where('is_active', true)
            ->get();

        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            $value = $request->input($fieldName);
            
            $existingValue = $employee->customFieldValues()
                ->where('custom_field_id', $field->id)
                ->first();

            if ($value !== null && $value !== '') {
                if ($existingValue) {
                    $existingValue->update(['value' => $value]);
                } else {
                    $employee->customFieldValues()->create([
                        'custom_field_id' => $field->id,
                        'value' => $value
                    ]);
                }
            } else {
                if ($existingValue) {
                    $existingValue->delete();
                }
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'تم تحديث الموظف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }

    /**
     * Get employees by branch for AJAX requests
     */
    public function getByBranch(Branch $branch)
    {
        $employees = $branch->employees;
        return response()->json($employees);
    }

    /**
     * Search employees for AJAX requests
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $employees = Employee::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  // Arabic character variations
                  ->orWhere('name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $query) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ى', 'ي', $query) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ؤ', 'و', $query) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ئ', 'ي', $query) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ة', 'ه', $query) . "%");
            })
            ->with('branch')
            ->limit(10)
            ->get();
        
        return response()->json($employees);
    }
}
