<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tool::with('employee');

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

        // Search by name or serial number with Arabic character normalization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  // Arabic character variations
                  ->orWhere('name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ؤ', 'و', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ئ', 'ي', $search) . "%")
                  ->orWhere('name', 'like', "%" . str_replace('ة', 'ه', $search) . "%")
                  ->orWhere('type', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('type', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhere('type', 'like', "%" . str_replace('ؤ', 'و', $search) . "%")
                  ->orWhere('type', 'like', "%" . str_replace('ئ', 'ي', $search) . "%")
                  ->orWhere('type', 'like', "%" . str_replace('ة', 'ه', $search) . "%");
            });
        }

        $tools = $query->latest()->get();
        return view('tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::all();
        $selectedEmployeeId = $request->get('employee_id');
        $customFields = \App\Models\CustomField::where('applies_to', 'tool')
                                                ->where('is_active', true)
                                                ->orderBy('sort_order')
                                                ->get();
        return view('tools.create', compact('employees', 'selectedEmployeeId', 'customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255|unique:tools',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged',
            'assigned_employee_id' => 'nullable|exists:employees,id',
        ]);

        $tool = Tool::create($request->all());

        // Save custom field values
        $customFields = \App\Models\CustomField::where('applies_to', 'tool')->where('is_active', true)->get();
        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            if ($request->has($fieldName) && $request->$fieldName !== null && $request->$fieldName !== '') {
                $tool->customFieldValues()->create([
                    'custom_field_id' => $field->id,
                    'value' => $request->$fieldName
                ]);
            }
        }

        return redirect()->route('tools.index')
            ->with('success', 'تم إضافة الأداة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        $tool->load('employee', 'customFieldValues.customField');
        return view('tools.show', compact('tool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        $employees = Employee::all();
        $customFields = \App\Models\CustomField::where('applies_to', 'tool')
                                                ->where('is_active', true)
                                                ->orderBy('sort_order')
                                                ->get();
        $tool->load('customFieldValues.customField');
        return view('tools.edit', compact('tool', 'employees', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255|unique:tools,serial_number,' . $tool->id,
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,assigned,maintenance,damaged',
            'assigned_employee_id' => 'nullable|exists:employees,id',
        ]);

        $tool->update($request->all());

        // Update custom field values
        $customFields = \App\Models\CustomField::where('applies_to', 'tool')->where('is_active', true)->get();
        foreach ($customFields as $field) {
            $fieldName = 'custom_field_' . $field->id;
            $existingValue = $tool->customFieldValues()->where('custom_field_id', $field->id)->first();
            
            if ($request->has($fieldName) && $request->$fieldName !== null && $request->$fieldName !== '') {
                if ($existingValue) {
                    $existingValue->update(['value' => $request->$fieldName]);
                } else {
                    $tool->customFieldValues()->create([
                        'custom_field_id' => $field->id,
                        'value' => $request->$fieldName
                    ]);
                }
            } elseif ($existingValue) {
                $existingValue->delete();
            }
        }

        return redirect()->route('tools.index')
            ->with('success', 'تم تحديث الأداة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool)
    {
        $tool->delete();

        return redirect()->route('tools.index')
            ->with('success', 'تم حذف الأداة بنجاح');
    }

    /**
     * Assign tool to employee
     */
    public function assign(Request $request, Tool $tool)
    {
        $request->validate([
            'assigned_employee_id' => 'required|exists:employees,id',
        ]);

        $tool->update([
            'assigned_employee_id' => $request->assigned_employee_id,
            'status' => 'assigned',
        ]);

        return redirect()->route('tools.index')
            ->with('success', 'تم تخصيص الأداة للموظف بنجاح');
    }

    /**
     * Return tool from employee
     */
    public function returnTool(Tool $tool)
    {
        $tool->update([
            'assigned_employee_id' => null,
            'status' => 'available',
        ]);

        return redirect()->route('tools.index')
            ->with('success', 'تم استرداد الأداة بنجاح');
    }
}
