<x-layouts::app :title="__('Registered Courses')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-teal-600 to-cyan-500 px-6 py-5 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Registered Courses</h1>
                <p class="mt-1 text-sm text-teal-100">Courses registered under programmes of study.</p>
            </div>
            <a href="{{ route('registered-courses.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-teal-600 shadow hover:bg-teal-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Register Courses
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filters --}}
        @php
            $filterKeys = ['search', 'session', 'semester', 'level_id', 'programme_id', 'programme_type_id', 'faculty_id', 'department_id', 'programme_of_study_id', 'specialization_id'];
            $activeFilterCount = collect($filterKeys)->filter(fn ($key) => request()->filled($key))->count();
        @endphp
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm"
             x-data="registeredCoursesFilter({
                 facultyId: '{{ request('faculty_id') }}',
                 departmentId: '{{ request('department_id') }}',
                 programmeId: '{{ request('programme_id') }}',
                 departmentsUrlTemplate: '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
                 programmeOfStudiesUrl: '{{ route('registered-courses.programme-of-studies') }}',
                 specializationsUrl: '{{ route('registered-courses.specializations') }}',
             })">
            <div class="flex items-center justify-between gap-3 border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-teal-500 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.78 1.63l-6.28 7.54v6.23a1 1 0 01-.55.9l-4 2A1 1 0 019 20.5v-8.33L2.22 4.63A1 1 0 013 4z" />
                    </svg>
                    <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Filters</h2>
                </div>
                @if ($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-teal-600 dark:bg-teal-500 px-2.5 py-1 text-xs font-semibold text-white">
                        {{ $activeFilterCount }} {{ Str::plural('filter', $activeFilterCount) }} active
                    </span>
                @endif
            </div>

            <form method="GET" action="{{ route('registered-courses.list') }}" class="flex flex-col gap-6 p-6">

                <flux:field>
                    <flux:label for="search">Search</flux:label>
                    <flux:input
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by code or title…"
                        icon="magnifying-glass"
                    />
                </flux:field>

                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-zinc-400">Course &amp; Session</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <flux:field>
                            <flux:label for="session">Session</flux:label>
                            <flux:select id="session" name="session">
                                <option value="">All Sessions</option>
                                @foreach ($sessions as $year => $label)
                                    <option value="{{ $year }}" @selected(request('session') == $year)>{{ $label }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="semester">Semester</flux:label>
                            <flux:select id="semester" name="semester">
                                <option value="">All Semesters</option>
                                <option value="1" @selected(request('semester') == 1)>1st Semester</option>
                                <option value="2" @selected(request('semester') == 2)>2nd Semester</option>
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="level_id">Level</flux:label>
                            <flux:select id="level_id" name="level_id">
                                <option value="">All Levels</option>
                                @foreach ($levels as $id => $label)
                                    <option value="{{ $id }}" @selected(request('level_id') == $id)>{{ $label }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="programme_type_id">Programme Type</flux:label>
                            <flux:select id="programme_type_id" name="programme_type_id">
                                <option value="">All Programme Types</option>
                                @foreach ($programmeTypes as $type)
                                    <option value="{{ $type->id }}" @selected(request('programme_type_id') == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-zinc-400">Programme &amp; Location</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                        <flux:field>
                            <flux:label for="faculty_id">Faculty</flux:label>
                            <flux:select id="faculty_id" name="faculty_id" x-model="facultyId" x-on:change="onFacultyChange()">
                                <option value="">All Faculties</option>
                                @foreach ($faculties as $fac)
                                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="department_id">Department</flux:label>
                            <flux:select id="department_id" name="department_id" x-model="departmentId" x-on:change="onDepartmentChange()" x-bind:disabled="departments.length === 0">
                                <option value="">
                                    <span x-text="departments.length === 0 ? '— Select a faculty first —' : 'All Departments'">— Select a faculty first —</span>
                                </option>
                                <template x-for="dept in departments" :key="dept.id">
                                    <option :value="dept.id" x-text="dept.department_name"></option>
                                </template>
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="programme_id">Programme</flux:label>
                            <flux:select id="programme_id" name="programme_id" x-model="programmeId" x-on:change="onProgrammeChange()">
                                <option value="">All Programmes</option>
                                @foreach ($programmes as $prog)
                                    <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="programme_of_study_id">Programme of Study</flux:label>
                            <flux:select id="programme_of_study_id" name="programme_of_study_id" x-bind:disabled="programmeOfStudies.length === 0">
                                <option value="">
                                    <span x-text="programmeOfStudies.length === 0 ? '— Select department and programme first —' : 'All Programmes of Study'">— Select department and programme first —</span>
                                </option>
                                <template x-for="pos in programmeOfStudies" :key="pos.id">
                                    <option :value="pos.id" x-text="pos.name" :selected="String(pos.id) === '{{ request('programme_of_study_id') }}'"></option>
                                </template>
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label for="specialization_id">Specialization</flux:label>
                            <flux:select id="specialization_id" name="specialization_id" x-bind:disabled="specializations.length === 0">
                                <option value="">
                                    <span x-text="specializations.length === 0 ? '— Select a department first —' : 'All Specializations'">— Select a department first —</span>
                                </option>
                                <template x-for="spec in specializations" :key="spec.id">
                                    <option :value="spec.id" x-text="spec.name" :selected="String(spec.id) === '{{ request('specialization_id') }}'"></option>
                                </template>
                            </flux:select>
                        </flux:field>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-t border-zinc-100 dark:border-zinc-800 pt-5">
                    <flux:button type="submit" variant="filled">Apply Filters</flux:button>
                    @if ($activeFilterCount > 0)
                        <flux:button href="{{ route('registered-courses.list') }}" wire:navigate>Clear All</flux:button>
                    @endif
                </div>
            </form>
        </div>

        {{-- Applied Filters --}}
        @if ($activeFilterCount > 0)
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Showing results for:</span>

                @if (request()->filled('search'))
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Search: "{{ request('search') }}"
                    </span>
                @endif
                @if (request()->filled('session'))
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Session: {{ $sessions[request()->integer('session')] ?? request('session') }}
                    </span>
                @endif
                @if (request()->filled('semester'))
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Semester: {{ request('semester') == 1 ? '1st Semester' : '2nd Semester' }}
                    </span>
                @endif
                @if (request()->filled('level_id'))
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Level: {{ $levels[request()->integer('level_id')] ?? 'Year '.request('level_id') }}
                    </span>
                @endif
                @if ($selectedFaculty)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Faculty: {{ $selectedFaculty->faculty_name }}
                    </span>
                @endif
                @if ($selectedDepartment)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Department: {{ $selectedDepartment->department_name }}
                    </span>
                @endif
                @if ($selectedProgramme)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Programme: {{ $selectedProgramme->name }}
                    </span>
                @endif
                @if ($selectedProgrammeType)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Programme Type: {{ $selectedProgrammeType->name }}
                    </span>
                @endif
                @if ($selectedProgrammeOfStudy)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Programme of Study: {{ $selectedProgrammeOfStudy->name }}
                    </span>
                @endif
                @if ($selectedSpecialization)
                    <span class="inline-flex items-center rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-xs font-medium text-teal-700 dark:text-teal-300">
                        Specialization: {{ $selectedSpecialization->name }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Results Summary --}}
        <div class="flex items-center justify-between gap-3 rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 px-6 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        @if ($registeredCourses->total() > 0)
                            Showing {{ $registeredCourses->firstItem() }}–{{ $registeredCourses->lastItem() }} of {{ $registeredCourses->total() }} registered {{ Str::plural('course', $registeredCourses->total()) }}
                        @else
                            No registered courses found
                        @endif
                    </p>
                    <p class="text-xs text-zinc-400">
                        {{ $activeFilterCount > 0 ? 'Matching the selected filters' : 'Across all sessions and programmes' }}
                    </p>
                </div>
            </div>
            @if ($registeredCourses->hasPages())
                <span class="hidden sm:inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-800 px-3 py-1 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    Page {{ $registeredCourses->currentPage() }} of {{ $registeredCourses->lastPage() }}
                </span>
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-teal-50 dark:bg-teal-900/40 text-left text-xs font-semibold uppercase tracking-wider text-teal-600 dark:text-teal-300">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Registered On</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-teal-50 dark:divide-teal-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($registeredCourses as $registeredCourse)
                        <tr class="hover:bg-teal-50/60 dark:hover:bg-teal-900/20 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $registeredCourses->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs font-semibold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/40 px-2 py-0.5 rounded">
                                    {{ $registeredCourse->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-100">{{ $registeredCourse->title }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $registeredCourse->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('registered-courses.show', $registeredCourse) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-teal-500 hover:bg-teal-50 hover:text-teal-700 transition-colors dark:hover:bg-teal-900/30"
                                       title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('registered-courses.edit', $registeredCourse) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-500 hover:bg-amber-50 hover:text-amber-700 transition-colors dark:hover:bg-amber-900/30"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('registered-courses.destroy', $registeredCourse) }}"
                                          onsubmit="return confirm('Remove {{ addslashes($registeredCourse->code) }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors dark:hover:bg-red-900/30"
                                                title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm">No courses have been registered yet.</p>
                                    <a href="{{ route('registered-courses.index') }}" class="text-sm font-medium text-teal-500 hover:text-teal-700 hover:underline">
                                        Register the first course →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($registeredCourses->hasPages())
            <div>{{ $registeredCourses->links() }}</div>
        @endif

    </div>

<script>
function registeredCoursesFilter(config) {
    return {
        facultyId: config.facultyId || '',
        departmentId: config.departmentId || '',
        programmeId: config.programmeId || '',
        departments: [],
        loadingDepartments: false,
        programmeOfStudies: [],
        loadingProgrammeOfStudies: false,
        specializations: [],
        loadingSpecializations: false,

        init() {
            if (this.facultyId) this.loadDepartments(true);
        },

        onFacultyChange() {
            this.departmentId = '';
            this.loadDepartments(false);
        },

        onDepartmentChange() {
            this.loadSpecializations();
            this.loadProgrammeOfStudies();
        },

        onProgrammeChange() {
            this.loadProgrammeOfStudies();
        },

        loadDepartments(keepSelection) {
            this.departments = [];
            if (!keepSelection) {
                this.programmeOfStudies = [];
                this.specializations = [];
            }
            if (!this.facultyId) return;

            this.loadingDepartments = true;
            const url = config.departmentsUrlTemplate.replace('__ID__', this.facultyId);
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => {
                    this.departments = data;
                    if (keepSelection && this.departmentId) {
                        this.loadSpecializations();
                        if (this.programmeId) this.loadProgrammeOfStudies();
                    }
                })
                .catch(() => { this.departments = []; })
                .finally(() => { this.loadingDepartments = false; });
        },

        loadProgrammeOfStudies() {
            this.programmeOfStudies = [];
            if (!this.departmentId || !this.programmeId) return;

            this.loadingProgrammeOfStudies = true;
            const url = `${config.programmeOfStudiesUrl}?department_id=${this.departmentId}&programme_id=${this.programmeId}`;
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { this.programmeOfStudies = data; })
                .catch(() => { this.programmeOfStudies = []; })
                .finally(() => { this.loadingProgrammeOfStudies = false; });
        },

        loadSpecializations() {
            this.specializations = [];
            if (!this.departmentId) return;

            this.loadingSpecializations = true;
            const url = `${config.specializationsUrl}?department_id=${this.departmentId}`;
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { this.specializations = data; })
                .catch(() => { this.specializations = []; })
                .finally(() => { this.loadingSpecializations = false; });
        }
    }
}
</script>
</x-layouts::app>
