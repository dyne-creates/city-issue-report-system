<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Barangay;
use App\Models\Department;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('users')
            // left join: this ensures every user appears in the list, and the related fields are simply NULL when they don't apply.
            ->leftJoin('barangays', 'users.barangay_id', '=', 'barangays.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.role',
                'users.contact_number',
                'barangays.name AS barangay_name',
                'departments.name AS department_name'
            );

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('users.name', 'like', '%' . $request->search . '%')
                    ->orWhere('users.email', 'like', '%' . $request->search . '%')
                    ->orWhere('users.contact_number', 'like', '%' . $request->search . '%');
            });
        }
        // search by role
        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }
        // search by department
        if ($request->filled('department_id')) {
            $query->where('users.department_id', $request->department_id);
        }

        $departments = Department::all()->sortBy('name');

        $resultCount = (clone $query)->count();

        $users = $query->orderBy('users.role')->orderBy('users.name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'resultCount', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangays = Barangay::all()->sortBy('name'); // sort the barangay by name (A-Z)
        $departments = Department::all()->sortBy('name'); // sort the department by name (A-Z)

        return view('admin.users.create', compact('barangays', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        DB::table('users')->insert([
            'barangay_id' => $this->barangayForRole($validated),
            'department_id' => $this->departmentForRole($validated),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'contact_number' => $validated['contact_number'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.users.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $barangays = Barangay::all()->sortBy('name');
        $departments = Department::all()->sortBy('name');

        return view('admin.users.edit', compact('user', 'barangays', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $validated = $request->validated();

        $values = [
            'barangay_id' => $this->barangayForRole($validated),
            'department_id' => $this->departmentForRole($validated),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'contact_number' => $validated['contact_number'] ?? null,
            'updated_at' => now(),
        ];

        if (! empty($validated['password'])) {
            $values['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($values);

        return redirect()->route('admin.users.index', $request->only('page', 'search'))
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index', [
                    'page' => $request->page,
                ])->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        $page = max((int) $request->page, 1);

        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $total = $query->count();

        $lastPage = max((int) ceil($total / 10), 1);

        if ($page > $lastPage) {
            $page = $lastPage;
        }

        return redirect()
            ->route('admin.barangays.index', [
                'page' => $page,
                'search' => $request->search,
            ])->with('success', 'User deleted successfully.');
    }

    /**
     * Citizens belong to a barangay; staff/admin accounts do not.
     */
    private function barangayForRole(array $validated): ?int
    {
        return $validated['role'] === 'citizen'
            ? (int) $validated['barangay_id']
            : null;
    }

    /**
     * Staff belong to one department; citizen/admin accounts do not.
     */
    private function departmentForRole(array $validated): ?int
    {
        return $validated['role'] === 'staff'
            ? (int) $validated['department_id']
            : null;
    }
}
