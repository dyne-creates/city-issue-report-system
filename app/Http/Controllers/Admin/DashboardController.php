<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'barangays' => DB::table('barangays')->count(),
            'departments' => DB::table('departments')->count(),
            'categories' => DB::table('categories')->count(),
            'users' => DB::table('users')->count(),
            'issues' => DB::table('issues')->count(),
            'reported_issues' => DB::table('issues')->where('status', 'reported')->count(),
            'completed_issues' => DB::table('issues')->where('status', 'completed')->count(),
        ];

        return view('admin.dashboard', compact('counts'));
    }
}
