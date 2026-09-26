<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserRepository
{
    public function paginate(
        ?string $search,
        ?int $roleId,
        ?int $departmentId,
        ?int $academicStaff,
        int $perPage = 15
    ): LengthAwarePaginator {
        return User::with(['role', 'department'])
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($roleId, fn ($q) => $q->where('role_id', $roleId))
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->when($academicStaff !== null, fn ($q) => $q->whereHas(
                'role',
                fn ($q) => $q->where('academic_staff', $academicStaff)
            ))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function allRoles(): Collection
    {
        return Role::all();
    }

    public function allFaculties(): Collection
    {
        return DB::connection('test')->table('faculties')
            ->where('status', 0)
            ->orderBy('faculty_name')
            ->get();
    }

    public function allDepartments(): Collection
    {
        return DB::connection('test')->table('departments')
            ->orderBy('department_name')
            ->get();
    }

    public function findRole(int $roleId): ?Role
    {
        return Role::find($roleId);
    }

    public function departmentBelongsToFaculty(int $facultyId, int $departmentId): bool
    {
        return DB::connection('test')->table('departments')
            ->where('faculty_id', $facultyId)
            ->where('id', $departmentId)
            ->exists();
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'uuid' => Str::uuid()->toString(),
            'email' => $data['email'],
            'password' => '@123456789@',
            'title' => $data['title'] ?? null,
            'role_id' => $data['role_id'],
            'active' => 1,
            'faculty_id' => $data['faculty_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'user_id' => auth()->id(),
        ]);
    }

    public function updateUser(User $user, array $data): bool
    {
        return $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'title' => $data['title'] ?? null,
            'role_id' => $data['role_id'],
            'faculty_id' => $data['faculty_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
        ]);
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }
}
