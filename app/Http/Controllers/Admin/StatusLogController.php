<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatus_LogRequest;
use App\Http\Requests\UpdateStatus_LogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('status_logs')
            ->join('issues', 'status_logs.issue_id', '=', 'issues.id')
            ->join('users', 'status_logs.changed_by', '=', 'users.id')
            ->select(
                'status_logs.id',
                'status_logs.issue_id',
                'status_logs.old_status',
                'status_logs.new_status',
                'status_logs.remarks',
                'status_logs.created_at',
                'issues.title AS issue_title',
                'users.name AS changed_by_name'
            );

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('issues.title', 'like', '%'.$request->search.'%')
                    ->orWhere('users.name', 'like', '%'.$request->search.'%')
                    ->orWhere('status_logs.remarks', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('new_status')) {
            $query->where('status_logs.new_status', $request->new_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('status_logs.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('status_logs.created_at', '<=', $request->date_to);
        }

        $resultCount = (clone $query)->count();
        $statusLogs = $query->orderByDesc('status_logs.created_at')->paginate(10)->withQueryString();

        return view('admin.status-logs.index', compact('statusLogs', 'resultCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.status-logs.index')
            ->with('error', 'Status logs are created automatically when an issue status changes.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatus_LogRequest $request)
    {
        return redirect()->route('admin.status-logs.index')
            ->with('error', 'Status logs are created automatically when an issue status changes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $statusLog = DB::table('status_logs')
            ->join('issues', 'status_logs.issue_id', '=', 'issues.id')
            ->join('users', 'status_logs.changed_by', '=', 'users.id')
            ->select('status_logs.*', 'issues.title AS issue_title', 'users.name AS changed_by_name')
            ->where('status_logs.id', $id)
            ->first();

        abort_if(! $statusLog, 404);

        return view('admin.status-logs.show', compact('statusLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.status-logs.index')
            ->with('error', 'Status logs are audit records and cannot be edited.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatus_LogRequest $request, string $id)
    {
        return redirect()->route('admin.status-logs.index')
            ->with('error', 'Status logs are audit records and cannot be edited.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('admin.status-logs.index')
            ->with('error', 'Status logs are audit records and cannot be deleted.');
    }
}
