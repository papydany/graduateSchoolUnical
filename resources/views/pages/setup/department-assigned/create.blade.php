<x-layouts::app :title="__('Assign Department Coordinator')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl"
     x-data="departmentAssignedForm(
         '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
         '{{ route('setup.department-assigned.coordinators') }}'
     )">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.department-assigned.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-violet-200 hover:bg-violet-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Assign Department Coordinator</h1>
                <p class="text-sm text-violet-100 mt-0.5">Select a faculty, choose a department, then the user(s) to assign as coordinator.</p>
            </div>
        </div>
    </div>

    {{-- Flash / Validation Messages --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('setup.department-assigned.store') }}" class="flex flex-col gap-6">
        @csrf

        {{-- Faculty & Departments --}}
        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Departments</h2>
            </div>

            <div class="p-6 flex flex-col gap-5">

                {{-- Faculty --}}
                <flux:field>
                    <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                    <flux:select
                        id="faculty_id"
                        name="faculty_id"
                        x-on:change="loadDepartments($event.target.value)"
                    >
                        <option value="">— Select faculty —</option>
                        @foreach ($faculties as $fac)
                            <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                {{-- Department (radio, depends on selected faculty) --}}
                <div>
                    <flux:label>Department <span class="text-red-500">*</span></flux:label>

                    <p x-show="!loadingDepartments && departments.length === 0" class="text-sm text-zinc-400 py-2">
                        Select a faculty to see its departments.
                    </p>

                    <div x-show="departments.length > 0"
                         class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2 max-h-64 overflow-y-auto rounded-lg border border-zinc-100 dark:border-zinc-800 p-3">
                        <template x-for="dept in departments" :key="dept.id">
                            <label class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-zinc-700 dark:text-zinc-200 hover:bg-violet-50 dark:hover:bg-violet-900/20 cursor-pointer">
                                <input type="radio" name="department_id" :value="dept.id"
                                       x-on:change="selectDepartment(dept.id)"
                                       class="border-zinc-300 text-violet-600 focus:ring-violet-500">
                                <span x-text="dept.department_name"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Department Coordinators: only shown once a department is selected --}}
        <div x-show="selectedDepartment" x-cloak
             class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Department Coordinators</h2>
            </div>

            <div class="p-6">
                <p x-show="coordinatorsLoaded && coordinators.length === 0" class="text-sm text-zinc-400">
                    No department coordinator is currently assigned to this department.
                </p>

                <div x-show="coordinators.length > 0"
                     class="grid grid-cols-1 gap-2 sm:grid-cols-2 max-h-64 overflow-y-auto rounded-lg border border-zinc-100 dark:border-zinc-800 p-3">
                    <template x-for="coordinator in coordinators" :key="coordinator.id">
                        <label class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-violet-50 dark:hover:bg-violet-900/20 cursor-pointer">
                            <input type="checkbox" name="user_ids[]" :value="coordinator.id"
                                   class="rounded border-zinc-300 text-violet-600 focus:ring-violet-500">
                            <span class="flex flex-col">
                                <span class="font-medium text-zinc-800 dark:text-zinc-100" x-text="coordinator.name"></span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="coordinator.email"></span>
                            </span>
                        </label>
                    </template>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 hover:bg-violet-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Assignment
            </button>
            <a href="{{ route('setup.department-assigned.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

    {{-- Processing Modal --}}
    <div x-show="processing" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
        <div class="flex flex-col items-center gap-3 rounded-xl bg-white dark:bg-zinc-900 px-8 py-6 shadow-xl">
            <svg class="size-8 animate-spin text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Processing…</p>
        </div>
    </div>

</div>

<script>
function departmentAssignedForm(departmentsUrlTemplate, coordinatorsUrl) {
    return {
        departments: [],
        selectedDepartment: null,
        coordinators: [],
        coordinatorsLoaded: false,
        loadingDepartments: false,
        loadingCoordinators: false,

        get processing() {
            return this.loadingDepartments || this.loadingCoordinators;
        },

        loadDepartments(facultyId) {
            this.departments = [];
            this.selectedDepartment = null;
            this.coordinators = [];
            this.coordinatorsLoaded = false;
            if (!facultyId) return;

            this.loadingDepartments = true;
            fetch(departmentsUrlTemplate.replace('__ID__', facultyId), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => { this.departments = data; })
            .catch(() => { this.departments = []; })
            .finally(() => { this.loadingDepartments = false; });
        },

        selectDepartment(departmentId) {
            this.selectedDepartment = departmentId;
            this.loadCoordinators(departmentId);
        },

        loadCoordinators(departmentId) {
            this.coordinators = [];
            this.coordinatorsLoaded = false;
            this.loadingCoordinators = true;

            const url = coordinatorsUrl + '?department_id=' + encodeURIComponent(departmentId);

            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => { this.coordinators = data; })
            .catch(() => { this.coordinators = []; })
            .finally(() => { this.coordinatorsLoaded = true; this.loadingCoordinators = false; });
        }
    }
}
</script>
</x-layouts::app>
