<?php

namespace App\Repositories;

use App\Models\facultyAssigned;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultyAssignedRepository
{
    /**
     * Roles that are assigned at faculty level:
     * deskofficer (2), facultyCoodinator (4), transcriptOfficer (5).
     */
    public const FACULTY_ROLE_IDS = [2, 4, 5];

    /**
     * List existing faculty-level assignments, optionally narrowed to a
     * single faculty, a single role and/or a user search.
     */
    public function paginate(?string $search, ?int $facultyId, ?int $roleId, int $perPage = 15): LengthAwarePaginatorContract
    {
        return facultyAssigned::query()
            ->with(['user', 'role'])
            ->whereIn('role_id', self::FACULTY_ROLE_IDS)
            ->when($roleId, fn ($q) => $q->where('role_id', $roleId))
            ->when($facultyId, fn ($q) => $q->where('faculty_id', $facultyId))
            ->when($search, fn ($q) => $q->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function facultyOptions(): Collection
    {
        return DB::connection('test')->table('faculties')->pluck('faculty_name', 'id');
    }

    public function roleOptions(): Collection
    {
        return Role::whereIn('id', self::FACULTY_ROLE_IDS)->pluck('name', 'id');
    }

    public function faculties(): Collection
    {
        return DB::connection('test')->table('faculties')
            ->where('status', 0)
            ->orderBy('faculty_name')
            ->get();
    }

    /**
     * Users holding one of the faculty-level roles, eligible for assignment.
     */
    public function assignableUsers(): EloquentCollection
    {
        return User::with('role')
            ->whereIn('role_id', self::FACULTY_ROLE_IDS)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role_id']);
    }

    /**
     * Assign a user to the faculty under the given role. Uniqueness of
     * faculty + role is enforced by the controller's validation.
     */
    public function createAssignment(int $facultyId, int $roleId, int $userId, int $addedBy): facultyAssigned
    {
        return facultyAssigned::create([
            'uuid' => Str::uuid()->toString(),
            'faculty_id' => $facultyId,
            'user_id' => $userId,
            'role_id' => $roleId,
            'added_by' => $addedBy,
            'deleted_by' => 0,
        ]);
    }

    /**
     * Soft-delete an assignment, recording who removed it.
     */
    public function removeAssignment(facultyAssigned $facultyAssigned, int $deletedBy): void
    {
        $facultyAssigned->update(['deleted_by' => $deletedBy]);
        $facultyAssigned->delete();
    }
}
