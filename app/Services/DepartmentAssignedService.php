<?php

namespace App\Services;

use App\Models\DepartmentAssigned;
use App\Repositories\DepartmentAssignedRepository;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class DepartmentAssignedService
{
    public function __construct(private readonly DepartmentAssignedRepository $repository)
    {
    }

    public function getIndexData(?string $search, ?int $departmentId): array
    {
        $departmentAssigned = $this->repository->paginate($search, $departmentId);
        $departments = $this->repository->departmentOptions();

        return compact('departmentAssigned', 'departments');
    }

    public function getCreateData(): array
    {
        $faculties = $this->repository->faculties();

        return compact('faculties');
    }

    public function getCoordinators(int $departmentId): EloquentCollection
    {
        return $this->repository->departmentCoordinators($departmentId);
    }

    public function assign(array $departmentIds, array $userIds, int $addedBy): int
    {
        return $this->repository->createAssignments($departmentIds, $userIds, $addedBy);
    }

    public function remove(DepartmentAssigned $departmentAssigned, int $deletedBy): void
    {
        $this->repository->removeAssignment($departmentAssigned, $deletedBy);
    }
}
