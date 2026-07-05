<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $counts = [
            'total' => DB::table('issues')->where('user_id', $userId)->count(),
            'reported' => DB::table('issues')->where('user_id', $userId)->where('status', 'reported')->count(),
            'verified' => DB::table('issues')->where('user_id', $userId)->where('status', 'verified')->count(),
            'in_progress' => DB::table('issues')->where('user_id', $userId)->where('status', 'in_progress')->count(),
            'completed' => DB::table('issues')->where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        $recentIssues = DB::table('issues')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.created_at',
                'barangays.name AS barangay_name',
                'categories.name AS category_name'
            )
            ->where('issues.user_id', $userId)
            ->orderByDesc('issues.created_at')
            ->limit(5)
            ->get();

        return view('citizen.dashboard', compact('counts', 'recentIssues'));
    }
}
