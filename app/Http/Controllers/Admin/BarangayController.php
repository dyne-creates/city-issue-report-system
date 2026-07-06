<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBarangayRequest;
use App\Http\Requests\UpdateBarangayRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Barangay;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('barangays');

        if ($request->filled('search')) {
            // example: SELECT * FROM barangays WHERE name LIKE '%Bakakeng%'
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        // its like SELECT COUNT(*) FROM barangays WHERE name LIKE '%Bakakeng%'
        $resultCount = (clone $query)->count(); // clone: one builder is for counting, one is for fetching data

        $barangays = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.barangays.index', compact('barangays', 'resultCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.barangays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBarangayRequest $request)
    {
        Barangay::create($request->validated());

        return redirect()->route('admin.barangays.index')
            ->with('success', 'Barangay created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barangay $barangay)
    {
        return view('admin.barangays.edit', compact('barangay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangayRequest $request, Barangay $barangay)
    {
        $barangay->update($request->validated());

        return redirect()
            ->route('admin.barangays.index', $request->only('page', 'search'))
            ->with('success', 'Barangay updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barangay $barangay, Request $request)
    {
        try {
            $barangay->delete(); // delete the requested record

            $page = max((int) $request->page, 1); // parse and the secure the current page

            $query = Barangay::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            } // reapply search filters to accurately calculate the remaining item count

            $total = $query->count(); // kukunin ung natitirang items after deletion to avoid redirecting to an empty page

            $lastPage = max((int) ceil($total / 10), 1); // calculate the new last page based on a 10-item pagination limit

            if ($page > $lastPage) {
                $page = $lastPage;
            } // if the item that was deleted is ung solo player sa page na un, babalik sya sa new final page

            return redirect()
                ->route('admin.barangays.index', [
                    'page' => $page,
                    'search' => $request->search,
                ])
                ->with('success', 'Barangay deleted successfully.');
        } catch (QueryException) {

            return redirect()                           // prevents unwanted internal request keys
                ->route('admin.barangays.index', $request->only('page', 'search'))
                ->with('error', 'Barangay cannot be deleted because it is still being used.');
        }
    }
}
