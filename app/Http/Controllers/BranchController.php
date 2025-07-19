<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CustomField;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customFields = CustomField::where('applies_to', 'branch')
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get();
        return view('branches.create', compact('customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $branch = Branch::create($request->all());

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'branch')
                                    ->where('is_active', true)
                                    ->get();

        foreach ($customFields as $customField) {
            $fieldName = 'custom_field_' . $customField->id;
            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                if ($value !== null && $value !== '') {
                    $branch->customFieldValues()->create([
                        'custom_field_id' => $customField->id,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('branches.index')
            ->with('success', 'تم إضافة الفرع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $customFields = CustomField::where('applies_to', 'branch')
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get();
        $branch->load('customFieldValues');
        return view('branches.edit', compact('branch', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $branch->update($request->all());

        // Handle custom fields
        $customFields = CustomField::where('applies_to', 'branch')
                                    ->where('is_active', true)
                                    ->get();

        foreach ($customFields as $customField) {
            $fieldName = 'custom_field_' . $customField->id;
            $existingValue = $branch->customFieldValues()
                                   ->where('custom_field_id', $customField->id)
                                   ->first();

            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                if ($value !== null && $value !== '') {
                    if ($existingValue) {
                        $existingValue->update(['value' => $value]);
                    } else {
                        $branch->customFieldValues()->create([
                            'custom_field_id' => $customField->id,
                            'value' => $value
                        ]);
                    }
                } else {
                    if ($existingValue) {
                        $existingValue->delete();
                    }
                }
            }
        }

        return redirect()->route('branches.index')
            ->with('success', 'تم تحديث الفرع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'تم حذف الفرع بنجاح');
    }
}
