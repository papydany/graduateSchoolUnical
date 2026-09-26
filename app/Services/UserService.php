<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use RuntimeException;

class UserService
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function getIndexData(?string $search, ?int $roleId, ?int $departmentId, ?int $academicStaff): array
    {
        $users = $this->repository->paginate($search, $roleId, $departmentId, $academicStaff);
        $roles = $this->repository->allRoles();
        $departments = $this->repository->allDepartments();

        return compact('users', 'roles', 'departments');
    }

    public function getCreateData(): array
    {
        $roles = $this->repository->allRoles();
        $faculties = $this->repository->allFaculties();

        return compact('roles', 'faculties');
    }

    public function getEditData(User $user): array
    {
        $roles = $this->repository->allRoles();
        $faculties = $this->repository->allFaculties();

        return compact('user', 'roles', 'faculties');
    }

    /**
     * @throws RuntimeException when the staff type/role/department combination is invalid
     */
    public function create(array $validated): User
    {
        $validated = $this->resolveStaffAssignment($validated);

        return $this->repository->createUser($validated);
    }

    /**
     * @throws RuntimeException when the staff type/role/department combination is invalid
     */
    public function update(User $user, array $validated): bool
    {
        $validated = $this->resolveStaffAssignment($validated);

        return $this->repository->updateUser($user, $validated);
    }

    public function delete(User $user): void
    {
        $this->repository->deleteUser($user);
    }

    /**
     * Validate the staff type/role/faculty/department combination and null
     * out faculty/department for non-academic staff.
     *
     * @throws RuntimeException when the combination is invalid
     */
    private function resolveStaffAssignment(array $validated): array
    {
        $isAcademicStaff = (int) $validated['academic_staff'] === 1;

        $role = $this->repository->findRole((int) $validated['role_id']);

        if (! $role || (int) $role->academic_staff !== (int) $isAcademicStaff) {
            throw new RuntimeException('Please select a role that matches the selected staff type.');
        }

        if (! $isAcademicStaff) {
            $validated['faculty_id'] = null;
            $validated['department_id'] = null;

            return $validated;
        }

        if (! $this->repository->departmentBelongsToFaculty(
            (int) $validated['faculty_id'],
            (int) $validated['department_id']
        )) {
            throw new RuntimeException('Please select a department that belongs to the selected faculty.');
        }

        return $validated;
    }
}
