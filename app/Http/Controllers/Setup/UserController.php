<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('search'), fn ($q) => $q
                ->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                }))
            ->when($request->filled('role_id'), fn ($q) => $q
                ->where('role_id', $request->role_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::all();

        return view('pages.setup.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('pages.setup.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'title'                 => ['nullable', 'string', 'max:50'],
            'role_id'               => ['required', 'exists:roles,id'],
        ]);

        try {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
                'title'    => $request->title,
                'role_id'  => $request->role_id,
            ]);
        } catch (\Throwable) {
            return back()->withInput()
                ->with('error', 'Could not create user. Please try again.');
        }

        return redirect()
            ->route('setup.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('role');

        return view('pages.setup.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('pages.setup.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'title'                 => ['nullable', 'string', 'max:50'],
            'role_id'               => ['required', 'exists:roles,id'],
        ]);

        try {
            $data = [
                'name'    => $request->name,
                'email'   => $request->email,
                'title'   => $request->title,
                'role_id' => $request->role_id,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);
        } catch (\Throwable) {
            return back()->withInput()
                ->with('error', 'Could not update user. Please try again.');
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
            $user->delete();
        } catch (\Throwable) {
            return redirect()
                ->route('setup.users.index')
                ->with('error', 'Could not delete user. Please try again.');
        }

        return redirect()
            ->route('setup.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
