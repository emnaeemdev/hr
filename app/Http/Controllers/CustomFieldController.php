<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customFields = CustomField::latest()->get();
        return view('custom-fields.index', compact('customFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('custom-fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,email,date,select,textarea,checkbox,radio',
            'label' => 'required|string|max:255',
            'options' => 'nullable|string',
            'applies_to' => 'required|in:employee,branch,tool,document,advance',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'label', 'field_type', 'applies_to', 'options', 'sort_order']);
        $data['is_required'] = $request->has('is_required');
        $data['is_active'] = $request->has('is_active');
        
        // Convert options from string to array for select fields
        if ($data['field_type'] === 'select' && !empty($data['options'])) {
            $data['options'] = array_filter(array_map('trim', explode("\n", $data['options'])));
        } else {
            $data['options'] = null;
        }

        CustomField::create($data);

        return redirect()->route('custom-fields.index')
            ->with('success', 'تم إضافة الحقل المخصص بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomField $customField)
    {
        return view('custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField)
    {
        return view('custom-fields.edit', compact('customField'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,email,date,select,textarea,checkbox,radio',
            'label' => 'required|string|max:255',
            'options' => 'nullable|string',
            'applies_to' => 'required|in:employee,branch,tool,document,advance',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'label', 'field_type', 'applies_to', 'options', 'sort_order']);
        $data['is_required'] = $request->has('is_required');
        $data['is_active'] = $request->has('is_active');
        
        // Convert options from string to array for select fields
        if ($data['field_type'] === 'select' && !empty($data['options'])) {
            $data['options'] = array_filter(array_map('trim', explode("\n", $data['options'])));
        } else {
            $data['options'] = null;
        }

        $customField->update($data);

        return redirect()->route('custom-fields.index')
            ->with('success', 'تم تحديث الحقل المخصص بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomField $customField)
    {
        $customField->delete();

        return redirect()->route('custom-fields.index')
            ->with('success', 'تم حذف الحقل المخصص بنجاح');
    }

    /**
     * Toggle field status
     */
    public function toggleStatus(CustomField $customField)
    {
        $customField->update(['is_active' => !$customField->is_active]);

        $status = $customField->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return redirect()->route('custom-fields.index')
            ->with('success', $status . ' الحقل المخصص');
    }
}
