<?php

namespace App\Services;

use App\Models\facultyAssigned;
use App\Repositories\FacultyAssignedRepository;

class FacultyAssignedService
{
    public function __construct(private readonly FacultyAssignedRepository $repository)
    {
    }

    public function getIndexData(?string $search, ?int $facultyId, ?int $roleId): array
    {
        $facultyAssigned = $this->repository->paginate($search, $facultyId, $roleId);
        $faculties = $this->repository->facultyOptions();
        $roles = $this->repository->roleOptions();

        return compact('facultyAssigned', 'faculties', 'roles');
    }

    public function getCreateData(): array
    {
        $faculties = $this->repository->faculties();
        $roles = $this->repository->roleOptions();
        $usersByRole = $this->repository->assignableUsers()->groupBy('role_id');

        return compact('faculties', 'roles', 'usersByRole');
    }

    public function assign(int $facultyId, int $roleId, int $userId, int $addedBy): facultyAssigned
    {
        return $this->repository->createAssignment($facultyId, $roleId, $userId, $addedBy);
    }

    public function remove(facultyAssigned $facultyAssigned, int $deletedBy): void
    {
        $this->repository->removeAssignment($facultyAssigned, $deletedBy);
    }
}
