<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->assignedIssues()
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.specific_location',
                'issues.created_at',
                'users.name AS citizen_name',
                'barangays.name AS barangay_name',
                'categories.name AS category_name'
            );

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('issues.title', 'like', '%' . $request->search . '%')
                    ->orWhere('issues.description', 'like', '%' . $request->search . '%')
                    ->orWhere('users.name', 'like', '%' . $request->search . '%')
                    ->orWhere('barangays.name', 'like', '%' . $request->search . '%')
                    ->orWhere('categories.name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('issues.status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('issues.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issues.created_at', '<=', $request->date_to);
        }

        $resultCount = (clone $query)->count();
        $issues = $query->orderByDesc('issues.created_at')->paginate(10)->withQueryString();

        return view('staff.issues.index', compact('issues', 'resultCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('staff.issues.index')
            ->with('error', 'Staff cannot create citizen issue reports.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        return redirect()->route('staff.issues.index')
            ->with('error', 'Staff cannot create citizen issue reports.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('staff.issues.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $issue = $this->findAssignedIssue($id);

        abort_if(! $issue, 404);

        // for history purpose
        $statusLogs = DB::table('status_logs')
            ->join('users', 'status_logs.changed_by', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->select(
                'status_logs.*',
                'users.name AS user_name',
                'users.role',
                'departments.name AS department_name',

                DB::raw("
                    CASE
                        WHEN users.role = 'admin' THEN CONCAT(users.name, ' (Admin)')
                        WHEN users.role = 'staff' THEN CONCAT(users.name, ' (', departments.name, ')')
                        WHEN users.role = 'citizen' THEN CONCAT(users.name, ' (Reporter)')
                        ELSE users.name
                    END AS changed_by_display
                "),
            )
            ->where('status_logs.issue_id', $id)
            ->orderBy('status_logs.created_at')
            ->get();

        $statuses = ['reported', 'verified', 'in_progress', 'completed'];

        return view('staff.issues.edit', compact('issue', 'statusLogs', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, string $id)
    {
        $validated = $request->validated();
        $issue = $this->findAssignedIssue($id);

        abort_if(! $issue, 404);

        DB::transaction(function () use ($id, $issue, $validated) {
            $newStatus = $validated['status'];

            // Staff can update workflow status only for issues assigned to their department.
            DB::table('issues')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'resolved_at' => $newStatus === 'completed'
                        ? ($validated['resolved_at'] ?? now())
                        : null,
                    'updated_at' => now(),
                ]);

            if ($issue->status !== $newStatus || ! empty($validated['remarks'])) {
                DB::table('status_logs')->insert([
                    'issue_id' => $id,
                    'changed_by' => Auth::id(),
                    'old_status' => $issue->status,
                    'new_status' => $newStatus,
                    'remarks' => $validated['remarks'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('staff.issues.index')
            ->with('success', 'Issue status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('staff.issues.index')
            ->with('error', 'Staff cannot delete citizen issue reports.');
    }

    private function assignedIssues()
    {
        return DB::table('issues')
            ->join('users', 'issues.user_id', '=', 'users.id')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->where('categories.department_id', Auth::user()->department_id);
    }

    private function findAssignedIssue(string $id): ?object
    {
        return $this->assignedIssues()
            ->select(
                'issues.*',
                'users.name AS citizen_name',
                'barangays.name AS barangay_name',
                'categories.name AS category_name'
            )
            ->where('issues.id', $id)
            ->first();
    }
}
