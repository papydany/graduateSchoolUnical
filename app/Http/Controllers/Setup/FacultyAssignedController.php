<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\facultyAssigned;
use App\Repositories\FacultyAssignedRepository;
use App\Services\FacultyAssignedService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FacultyAssignedController extends Controller
{
    public function __construct(private readonly FacultyAssignedService $facultyAssignedService)
    {
    }

    /**
     * List users assigned to faculties as desk officer,
     * faculty coordinator or transcript officer.
     */
    public function index(Request $request)
    {
        $search = $request->string('search')->value() ?: null;
        $facultyId = $request->integer('faculty_id') ?: null;
        $roleId = $request->integer('role_id') ?: null;

        $data = $this->facultyAssignedService->getIndexData($search, $facultyId, $roleId);

        return view('pages.setup.faculty-assigned.index', $data);
    }

    /**
     * Show the assignment form. A role (desk officer, faculty coordinator
     * or transcript officer) is picked first, and only users holding
     * that role are listed for selection.
     */
    public function create(Request $request)
    {
        $selectedRoleId = (int) old('role_id', $request->integer('role_id')) ?: null;

        $data = $this->facultyAssignedService->getCreateData();

        return view('pages.setup.faculty-assigned.create', [
            ...$data,
            'selectedRoleId' => $selectedRoleId,
        ]);
    }

    /**
     * Assign a single user to a faculty under the selected role.
     * A faculty can only hold one active assignment per role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => ['required', 'integer', 'exists:test.faculties,id'],
            'role_id' => [
                'required',
                'integer',
                Rule::in(FacultyAssignedRepository::FACULTY_ROLE_IDS),
                Rule::unique('faculty_assigneds', 'role_id')
                    ->where('faculty_id', $request->integer('faculty_id'))
                    ->whereNull('deleted_at'),
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role_id', $request->integer('role_id')),
            ],
        ], [
            'role_id.unique' => 'This role has already been assigned for the selected faculty.',
            'user_id.exists' => 'The selected user does not hold the selected role.',
        ]);

        try {
            $this->facultyAssignedService->assign(
                facultyId: $validated['faculty_id'],
                roleId: $validated['role_id'],
                userId: $validated['user_id'],
                addedBy: auth()->id(),
            );
        } catch (UniqueConstraintViolationException) {
            // Another request assigned this faculty + role after validation ran.
            throw ValidationException::withMessages([
                'role_id' => 'This role has already been assigned for the selected faculty.',
            ]);
        }

        return redirect()
            ->route('setup.faculty-assigned.index')
            ->with('success', 'Assignment saved successfully.');
    }

    public function destroy(facultyAssigned $facultyAssigned)
    {
        $this->facultyAssignedService->remove($facultyAssigned, auth()->id());

        return redirect()
            ->back()
            ->with('success', 'User removed from the faculty successfully.');
    }
}
