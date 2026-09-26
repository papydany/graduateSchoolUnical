<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(Request $request)
    {
        $academicStaff = $request->query('academic_staff');
        $academicStaff = in_array($academicStaff, ['0', '1'], true) ? (int) $academicStaff : null;

        $data = $this->userService->getIndexData(
            $request->string('search')->value() ?: null,
            $request->integer('role_id') ?: null,
            $request->integer('department_id') ?: null,
            $academicStaff,
        );

        return view('pages.setup.users.index', $data);
    }

    public function create()
    {
        return view('pages.setup.users.create', $this->userService->getCreateData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'title' => ['nullable', 'string', 'max:50'],
            'academic_staff' => ['required', 'in:0,1'],
            'role_id' => ['required', 'exists:roles,id'],
            'faculty_id' => ['required_if:academic_staff,1', 'nullable', 'integer'],
            'department_id' => ['required_if:academic_staff,1', 'nullable', 'integer'],
        ]);

        try {
            $this->userService->create($validated);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Could not create user. Please try again.');
        }

        return redirect()
            ->route('setup.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'faculty', 'department']);

        return view('pages.setup.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('role');

        return view('pages.setup.users.edit', $this->userService->getEditData($user));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'title' => ['nullable', 'string', 'max:50'],
            'academic_staff' => ['required', 'in:0,1'],
            'role_id' => ['required', 'exists:roles,id'],
            'faculty_id' => ['required_if:academic_staff,1', 'nullable', 'integer'],
            'department_id' => ['required_if:academic_staff,1', 'nullable', 'integer'],
        ]);

        try {
            $this->userService->update($user, $validated);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Could not update user. Please try again.');
        }

        return redirect()
            ->route('setup.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()
                ->route('setup.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            $this->userService->delete($user);
        } catch (Throwable $e) {
            return redirect()
                ->route('setup.users.index')
                ->with('error', 'Could not delete user. Please try again.');
        }

        return redirect()
            ->route('setup.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
