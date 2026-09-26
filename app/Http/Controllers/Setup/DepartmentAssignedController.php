<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Services\DepartmentAssignedService;
use Illuminate\Http\Request;

class DepartmentAssignedController extends Controller
{
    public function __construct(private readonly DepartmentAssignedService $departmentAssignedService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->string('search')->value() ?: null;
        $departmentId = $request->integer('department_id') ?: null;

        $data = $this->departmentAssignedService->getIndexData($search, $departmentId);

        return view('pages.setup.department-assigned.index', $data);
    }

    public function create()
    {
        return view(
'pages.setup.department-assigned.create',
            $this->departmentAssignedService->getCreateData()
        );
    }

    /**
     * AJAX: return users with the "departmentCoordinator" role who are
     * already assigned to the given department, as JSON.
     */
    public function coordinators(Request $request)
    {
        $request->validate([
            'department_id' => ['required', 'integer'],
        ]);

        return response()->json(
            $this->departmentAssignedService->getCoordinators($request->integer('department_id'))
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $created = $this->departmentAssignedService->assign(
            departmentIds: [$validated['department_id']],
            userIds: $validated['user_ids'],
            addedBy: auth()->id(),
        );

        $message = $created > 0
            ? "{$created} assignment(s) saved successfully."
            : 'Those users are already assigned to the selected department.';

        return redirect()
            ->route('setup.department-assigned.index')
            ->with($created > 0 ? 'success' : 'error', $message);
    }
}
