<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeDocument;
use App\Models\Tool;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalBranches = Branch::count();
        $activeAdvances = EmployeeAdvance::where('status', 'active')->count();
        
        // Get recent employees (last 10)
        $recentEmployees = Employee::with('branch')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get pending tasks
        $pendingDocuments = EmployeeDocument::where('is_verified', false)->count();
        $incompleteEmployees = Employee::where('documents_complete', false)->count();
        
        // Count unreturned tools
        $unreturnedTools = Employee::whereHas('tools', function($query) {
            $query->where('employee_tool.return_status', 'assigned');
        })->count();
        
        return view('dashboard', compact(
            'totalEmployees',
            'activeEmployees', 
            'totalBranches',
            'activeAdvances',
            'recentEmployees',
            'pendingDocuments',
            'incompleteEmployees',
            'unreturnedTools'
        ));
    }
}
