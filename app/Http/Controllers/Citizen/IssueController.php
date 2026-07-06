<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Barangay;
use App\Models\Issue;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // retrieves the filters that were previously stored 
        $filters = session('citizen_issue_filters', []);

        $query = DB::table('issues')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.specific_location',
                'issues.created_at',
                'barangays.name AS barangay_name',
                'categories.name AS category_name',
                'departments.name AS department_name'
            )
            ->where('issues.user_id', Auth::id());
        // check if the search value exists
        if (! empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('issues.title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('issues.description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('barangays.name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('categories.name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('departments.name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (! empty($filters['status'])) {
            $query->where('issues.status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('issues.created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('issues.created_at', '<=', $filters['date_to']);
        }

        $resultCount = (clone $query)->count();
        $issues = $query->orderByDesc('issues.created_at')->paginate(10);

        return view('citizen.issues.index', compact('issues', 'resultCount', 'filters'));
    }

    public function search(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'in:reported,verified,in_progress,completed'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);
        // removes empty values
        session(['citizen_issue_filters' => array_filter($filters, fn($value) => filled($value))]);

        return redirect()->route('citizen.issues.index');
    }

    public function resetSearch()
    {
        session()->forget('citizen_issue_filters');

        return redirect()->route('citizen.issues.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangays = Barangay::all()->sortBy('name');

        $categories = DB::table('categories')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'categories.id',
                'categories.name',
                'departments.name AS department_name'
            )
            ->orderBy('departments.name')
            ->orderBy('categories.name')
            ->get();

        return view('citizen.issues.create', compact('barangays', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        $validated = $request->validated();
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('images', 'public');
        }

        DB::transaction(function () use ($validated, $photoPath) {
            // Citizens create reports; workflow status always starts as reported.
            $issueId = DB::table('issues')->insertGetId([
                'user_id' => Auth::id(),
                'barangay_id' => $validated['barangay_id'],
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'specific_location' => $validated['specific_location'] ?? null,
                'status' => 'reported',
                'photo_path' => $photoPath,
                'resolved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('status_logs')->insert([
                'issue_id' => $issueId,
                'changed_by' => Auth::id(),
                'old_status' => null,
                'new_status' => 'reported',
                'remarks' => 'Issue submitted by citizen.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('citizen.issues.index')
            ->with('success', 'Issue report submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $issue = DB::table('issues')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'issues.*',
                'barangays.name AS barangay_name',
                'categories.name AS category_name',
                'departments.name AS department_name'
            )
            ->where('issues.id', $id)
            ->where('issues.user_id', Auth::id())
            ->first();

        abort_if(! $issue, 404);

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

                DB::raw("DATE_FORMAT(status_logs.created_at, '%M %d, %Y %h:%i %p') AS formatted_date")
            )
            ->where('status_logs.issue_id', $id)
            ->orderBy('status_logs.created_at')
            ->get();

        return view('citizen.issues.show', compact('issue', 'statusLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        abort_unless($issue->user_id === Auth::id(), 403); // can only edit his own, not the records of the other citizens/ users

        if ($issue->status !== 'reported') {
            return redirect()
                ->route('citizen.issues.show', $issue)
                ->with('error', 'You can only edit a report while it is still in the Reported status.');
        }

        $barangays = Barangay::orderBy('name')->get();

        $categories = DB::table('categories')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'categories.id',
                'categories.name',
                'departments.name AS department_name'
            )
            ->orderBy('departments.name')
            ->orderBy('categories.name')
            ->get();

        return view('citizen.issues.edit', compact('issue', 'barangays', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        abort_unless($issue->user_id === Auth::id(), 403);

        if ($issue->status !== 'reported') {
            return redirect()
                ->route('citizen.issues.show', $issue)
                ->with('error', 'This report can no longer be edited.');
        }

        $validated = $request->validated();

        $data = [
            'barangay_id' => $validated['barangay_id'],
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'specific_location' => $validated['specific_location'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('images', 'public');
        }

        $issue->update($data);

        return redirect()
            ->route('citizen.issues.index', $issue)
            ->with('success', 'Issue report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        abort_unless($issue->user_id === Auth::id(), 403);

        if ($issue->status !== 'reported') {
            return redirect()
                ->route('citizen.issues.index')
                ->with('error', 'This report can no longer be deleted.');
        }

        $issue->delete();

        return redirect()
            ->route('citizen.issues.index')
            ->with('success', 'Issue report deleted successfully.');
    }
}
