<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\Employee;
use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeDocument::with(['employee', 'employee.branch']);

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by employee name or document type with Arabic character normalization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_type', 'like', "%{$search}%")
                  ->orWhere('document_name', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($empQuery) use ($search) {
                      $empQuery->where('name', 'like', "%{$search}%");
                  })
                  // Arabic character variations
                  ->orWhere('document_type', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('document_type', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhere('document_name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                  ->orWhere('document_name', 'like', "%" . str_replace('ى', 'ي', $search) . "%")
                  ->orWhereHas('employee', function ($empQuery) use ($search) {
                      $empQuery->where('name', 'like', "%" . str_replace(['أ', 'إ', 'آ'], 'ا', $search) . "%")
                               ->orWhere('name', 'like', "%" . str_replace('ى', 'ي', $search) . "%");
                  });
            });
        }

        $documents = $query->latest()->get();
        $branches = \App\Models\Branch::all();
        
        return view('employee-documents.index', compact('documents', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::all();
        $selectedEmployeeId = $request->get('employee_id');
        $customFields = CustomField::where('applies_to', 'document')
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get();
        return view('employee-documents.create', compact('employees', 'selectedEmployeeId', 'customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_type' => 'required|string|max:255',
            'document_name' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('documents', 'public');
        }

        $employeeDocument = EmployeeDocument::create($data);

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'document')
                                    ->where('is_active', true)
                                    ->get();

        foreach ($customFields as $customField) {
            $fieldName = 'custom_field_' . $customField->id;
            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                if ($customField->field_type === 'checkbox') {
                    $value = $request->has($fieldName) ? '1' : '0';
                }
                if (!empty($value) || $customField->field_type === 'checkbox') {
                    $employeeDocument->customFieldValues()->create([
                        'custom_field_id' => $customField->id,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('employee-documents.index')
            ->with('success', 'تم إضافة المستند بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeDocument $employeeDocument)
    {
        $employeeDocument->load('employee');
        return view('employee-documents.show', compact('employeeDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeDocument $employeeDocument)
    {
        $employees = Employee::all();
        $customFields = CustomField::where('applies_to', 'document')
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get();
        $employeeDocument->load('customFieldValues.customField');
        return view('employee-documents.edit', compact('employeeDocument', 'employees', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeDocument $employeeDocument)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_type' => 'required|string|max:255',
            'document_name' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($employeeDocument->file_path) {
                Storage::disk('public')->delete($employeeDocument->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('documents', 'public');
        }

        $employeeDocument->update($data);

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'document')
                                    ->where('is_active', true)
                                    ->get();

        foreach ($customFields as $customField) {
            $fieldName = 'custom_field_' . $customField->id;
            $existingValue = $employeeDocument->customFieldValues()
                                              ->where('custom_field_id', $customField->id)
                                              ->first();

            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                if ($customField->field_type === 'checkbox') {
                    $value = $request->has($fieldName) ? '1' : '0';
                }

                if ($existingValue) {
                    if (!empty($value) || $customField->field_type === 'checkbox') {
                        $existingValue->update(['value' => $value]);
                    } else {
                        $existingValue->delete();
                    }
                } else {
                    if (!empty($value) || $customField->field_type === 'checkbox') {
                        $employeeDocument->customFieldValues()->create([
                            'custom_field_id' => $customField->id,
                            'value' => $value
                        ]);
                    }
                }
            } else {
                if ($existingValue && $customField->field_type === 'checkbox') {
                    $existingValue->update(['value' => '0']);
                }
            }
        }

        return redirect()->route('employee-documents.index')
            ->with('success', 'تم تحديث المستند بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeDocument $employeeDocument)
    {
        // Delete file if exists
        if ($employeeDocument->file_path) {
            Storage::disk('public')->delete($employeeDocument->file_path);
        }

        $employeeDocument->delete();

        return redirect()->route('employee-documents.index')
            ->with('success', 'تم حذف المستند بنجاح');
    }

    /**
     * Verify document
     */
    public function verify(EmployeeDocument $employeeDocument)
    {
        $employeeDocument->update(['status' => 'verified']);

        return redirect()->route('employee-documents.index')
            ->with('success', 'تم التحقق من المستند');
    }

    /**
     * Reject document
     */
    public function reject(EmployeeDocument $employeeDocument)
    {
        $employeeDocument->update(['status' => 'rejected']);

        return redirect()->route('employee-documents.index')
            ->with('success', 'تم رفض المستند');
    }

    /**
     * Download document
     */
    public function download(EmployeeDocument $employeeDocument)
    {
        if (!$employeeDocument->file_path || !Storage::disk('public')->exists($employeeDocument->file_path)) {
            return redirect()->back()->with('error', 'الملف غير موجود');
        }

        return Storage::disk('public')->download($employeeDocument->file_path, $employeeDocument->document_name);
    }
}
