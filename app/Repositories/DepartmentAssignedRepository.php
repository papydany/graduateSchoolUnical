<?php

namespace App\Repositories;

use App\Models\DepartmentAssigned;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentAssignedRepository
{
 private const DEPARTMENT_COORDINATOR_ROLE_id = 3;
    /**
     * List existing department-coordinator (role_id 3) assignments,
     * optionally narrowed to a single department and/or a user search.
     */
    public function paginate(?string $search, ?int $departmentId, int $perPage = 15): LengthAwarePaginatorContract
    {
        return DepartmentAssigned::query()
            ->with(['user', 'role'])
            ->where('role_id', self::DEPARTMENT_COORDINATOR_ROLE_id)
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->when($search, fn ($q) => $q->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function departmentOptions(): Collection
    {
        return DB::connection('test')->table('departments')->pluck('department_name', 'id');
    }

    public function faculties(): Collection
    {
        return DB::connection('test')->table('faculties')
            ->where('status', 0)
            ->orderBy('faculty_name')
            ->get();
    }

    /**
     * Users with the departmentCoordinator role (role_id 3) who belong
     * to the given department.
     */
    public function departmentCoordinators(int $departmentId): EloquentCollection
    {
        return User::where('role_id', self::DEPARTMENT_COORDINATOR_ROLE_id)
            ->where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    /**
     * Assign each of the given users to each of the given departments as a
     * department coordinator, skipping any pairing that already exists.
     */
    public function createAssignments(array $departmentIds, array $userIds, int $addedBy): int
    {
        $roleId = self::DEPARTMENT_COORDINATOR_ROLE_id;
        $created = 0;

        foreach ($departmentIds as $departmentId) {
            foreach ($userIds as $userId) {
                $alreadyAssigned = DepartmentAssigned::where('department_id', $departmentId)
                    ->where('user_id', $userId)
                    ->exists();

                if ($alreadyAssigned) {
                    continue;
                }

                DepartmentAssigned::create([
                    'uuid' => Str::uuid()->toString(),
                    'department_id' => $departmentId,
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'added_by' => $addedBy,
                    'deleted_by' => 0,
                ]);

                $created++;
            }
        }

        return $created;
    }

    /**
     * Soft-delete an assignment, recording who removed it.
     */
    public function removeAssignment(DepartmentAssigned $departmentAssigned, int $deletedBy): void
    {
        $departmentAssigned->update(['deleted_by' => $deletedBy]);
        $departmentAssigned->delete();
    }
}
