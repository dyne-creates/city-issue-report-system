<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $departmentId = Auth::user()->department_id;

        $counts = [
            'assigned' => $this->issuesForDepartment($departmentId)->count(),
            'reported' => $this->issuesForDepartment($departmentId)->where('issues.status', 'reported')->count(),
            'verified' => $this->issuesForDepartment($departmentId)->where('issues.status', 'verified')->count(),
            'in_progress' => $this->issuesForDepartment($departmentId)->where('issues.status', 'in_progress')->count(),
            'completed' => $this->issuesForDepartment($departmentId)->where('issues.status', 'completed')->count(),
        ];

        $recentIssues = $this->issuesForDepartment($departmentId)
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.created_at',
                'users.name AS citizen_name',
                'barangays.name AS barangay_name',
                'categories.name AS category_name'
            )
            ->orderByDesc('issues.created_at')
            ->limit(5) // restricts the result set to a maximum of 5 records
            ->get();

        return view('staff.dashboard', compact('counts', 'recentIssues'));
    }

    private function issuesForDepartment(?int $departmentId)
    {
        return DB::table('issues')
            ->join('users', 'issues.user_id', '=', 'users.id')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->where('categories.department_id', $departmentId);
    }
}
