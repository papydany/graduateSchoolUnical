<x-layouts::app :title="__('Add Registered Courses')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-5xl"
     x-data="departmentLoader('{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}')">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
           
        </div>
    </div>
         @if (session('success'))
            <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif


    {{-- Flash / Validation Errors --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/30">
            <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @isset($course)
        {{-- Registrations for this course --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <div>
                    <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">
                        Registrations for {{ $course->code }}
                    </h2>
                    <p class="mt-0.5 text-xs text-teal-500 dark:text-teal-400">{{ $course->title }}</p>
                </div>
                <a href="{{ route('courses.show', $course) }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-teal-600 hover:text-teal-800 dark:text-teal-400">
                    ← Back to course
                </a>
            </div>

            @if ($registeredCourses->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                    This course has not been registered under any programme yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Semester</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Registered On</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($registeredCourses as $registeredCourse)
                                <tr class="hover:bg-teal-50/60 dark:hover:bg-teal-900/20 transition-colors">
                                    <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                        {{ $levels[$registeredCourse->level_id] ?? 'Year '.$registeredCourse->level_id }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                        {{ $registeredCourse->session }}/{{ $registeredCourse->session + 1 }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                        {{ $registeredCourse->semester == 1 ? '1st Semester' : '2nd Semester' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $registeredCourse->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('registered-courses.show', $registeredCourse) }}"
                                           class="text-sm font-medium text-teal-600 hover:text-teal-800 dark:text-teal-400">
                                            View →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endisset

    <form method="GET" action="{{ route('registered-courses.getCourses') }}" class="flex flex-col gap-6">
    

        {{-- Shared Context --}}
        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Department & Programme</h2>
                <p class="mt-0.5 text-xs text-indigo-500 dark:text-indigo-400">These apply to all courses in this submission.</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                    {{-- Faculty --}}
                    <flux:field>
                        <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                        <flux:select
                            id="faculty_id"
                            name="faculty_id"
                            :invalid="$errors->has('faculty_id')"
                            x-on:change="loadDepartments($event.target.value)"
                        >
                            <option value="">— Select faculty —</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->id }}" @selected(old('faculty_id', $facultyId ?? null) == $fac->id)>
                                    {{ $fac->faculty_name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="faculty_id" />
                    </flux:field>

                    {{-- Department --}}
                    <flux:field>
                        <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                        <div x-show="loading" class="flex items-center gap-2 text-sm text-indigo-400 py-2">
                            <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Loading…
                        </div>

                        <flux:select
                            id="department_id"
                            name="department_id"
                            :invalid="$errors->has('department_id')"
                            x-show="!loading"
                            x-bind:disabled="departments.length === 0"
                        >
                            <option value="">
                                <span x-text="departments.length === 0 ? '— Select a faculty first —' : '— Select department —'">
                                    — Select a faculty first —
                                </span>
                            </option>
                            <template x-for="dept in departments" :key="dept.id">
                                <option
                                    :value="dept.id"
                                    x-text="dept.department_name"
                                    :selected="dept.id == {{ old('department_id', $departmentId ?? 'null') }}"
                                ></option>
                            </template>
                        </flux:select>
                        <flux:error name="department_id" />
                    </flux:field>

                    {{-- Programme --}}
                    <flux:field>
                        <flux:label for="programme_id">Programme <span class="text-red-500">*</span></flux:label>
                        <flux:select id="programme_id" name="programme_id" :invalid="$errors->has('programme_id')">
                            <option value="">— Select programme —</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->id }}" @selected(old('programme_id', $programmeId ?? null) == $prog->id)>
                                    {{ $prog->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="programme_id" />
                    </flux:field>

                        {{-- Semester --}}
                    <flux:field>
                        <flux:label for="semester">Semester <span class="text-red-500">*</span></flux:label>
                        <flux:select id="semester" name="semester" :invalid="$errors->has('semester')">
                            <option value="">— Select semester —</option>
                      <option value="1" @selected(old('semester', $semesterInt ?? null) == 1)>First Semester</option>
                      <option value="2" @selected(old('semester', $semesterInt ?? null) == 2)>Second Semester</option>
                        </flux:select>
                        <flux:error name="semester" />
                    </flux:field>

                          {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
             Generate Courses
            </button>
      
        </div>
                </div>
            </div>
        </div>


    </form>

    @isset($query)
        <form method="POST" action="{{ route('registered-courses.store-many') }}"
              class="flex flex-col gap-6"
              x-data="{ selected: [], get allSelected() { return {{ $query->count() }} > 0 && this.selected.length === {{ $query->count() }} } }">
            @csrf

            {{-- Selected Context --}}
            <div class="rounded-xl border border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-500 dark:text-indigo-400 mb-2">Showing courses for</p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-zinc-800 px-3 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                        Faculty: {{ $selectedFaculty->faculty_name ?? '—' }}
                    </span>
                    <span class="inline-flex items-center rounded-full border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-zinc-800 px-3 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                        Department: {{ $selectedDepartment->department_name ?? '—' }}
                    </span>
                    <span class="inline-flex items-center rounded-full border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-zinc-800 px-3 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                        Programme: {{ $selectedProgramme->name ?? '—' }}
                    </span>
                    <span class="inline-flex items-center rounded-full border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-zinc-800 px-3 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                        Semester: {{ $semesterInt === 1 ? '1st Semester' : '2nd Semester' }}
                    </span>
                </div>
            </div>

            {{-- Matching Courses --}}
            <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                    <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Matching Courses</h2>
                    <p class="mt-0.5 text-xs text-indigo-500 dark:text-indigo-400">Select the courses you want to register.</p>
                </div>

                @if ($query->isEmpty())
                    <div class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        No courses found for the selected faculty, department, programme, and semester.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                                <tr>
                                    <th class="w-10 px-6 py-3">
                                        <input type="checkbox"
                                               class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500"
                                               x-bind:checked="allSelected"
                                               x-on:change="selected = $event.target.checked ? [{{ $query->pluck('id')->implode(',') }}] : []">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Semester</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach ($query as $course)
                                    <tr>
                                        <td class="px-6 py-3">
                                            <input type="checkbox" name="course_ids[]" value="{{ $course->id }}"
                                                   class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500"
                                                   x-model="selected">
                                        </td>
                                        <td class="px-6 py-3 text-sm font-mono font-semibold text-indigo-600 dark:text-indigo-400">{{ $course->code }}</td>
                                        <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ $course->title }}</td>
                                        <td class="px-6 py-3 text-sm text-zinc-600 dark:text-zinc-300">{{ $course->unit }}</td>
                                        <td class="px-6 py-3 text-sm text-zinc-600 dark:text-zinc-300">{{ $course->semester }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if ($query->isNotEmpty())
                {{-- Registration Destination --}}
                <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm"
                     x-data="destinationPicker({
                         departmentsUrlTemplate: '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
                         programmeOfStudiesUrl: '{{ route('registered-courses.programme-of-studies') }}',
                         specializationsUrl: '{{ route('registered-courses.specializations') }}',
                         oldProgrammeOfStudyId: '{{ old('programme_of_study_id') }}',
                     })">
                    <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                        <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Registration Destination</h2>
                        <p class="mt-0.5 text-xs text-indigo-500 dark:text-indigo-400">
                            Narrow down to the Programme of Study these selected courses will be registered under.
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Faculty (destination — narrows Department) --}}
                            <flux:field>
                                <flux:label for="dest_faculty_id">Faculty</flux:label>
                                <flux:select
                                    id="dest_faculty_id"
                                    x-model="facultyId"
                                    x-on:change="loadDepartments()"
                                >
                                    <option value="">— Select faculty —</option>
                                    @foreach ($faculties as $fac)
                                        <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                                    @endforeach
                                </flux:select>
                            </flux:field>

                            {{-- Department (destination — dependent on Faculty) --}}
                            <flux:field>
                                <flux:label for="dest_department_id">Department</flux:label>

                                <div x-show="loadingDepartments" class="flex items-center gap-2 text-sm text-indigo-400 py-2">
                                    <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Loading…
                                </div>

                                <flux:select
                                    id="dest_department_id"
                                    x-show="!loadingDepartments"
                                    x-model="departmentId"
                                    x-on:change="onDepartmentChange()"
                                    x-bind:disabled="departments.length === 0"
                                >
                                    <option value="">
                                        <span x-text="departments.length === 0 ? '— Select a faculty first —' : '— Select department —'">
                                            — Select a faculty first —
                                        </span>
                                    </option>
                                    <template x-for="dept in departments" :key="dept.id">
                                        <option :value="dept.id" x-text="dept.department_name"></option>
                                    </template>
                                </flux:select>
                            </flux:field>

                            {{-- Programme (degree type — narrows Programme of Study) --}}
                            <flux:field>
                                <flux:label for="dest_programme_id">Programme</flux:label>
                                <flux:select
                                    id="dest_programme_id"
                                    x-model="programmeId"
                                    x-on:change="onProgrammeChange()"
                                >
                                    <option value="">— Select programme —</option>
                                    @foreach ($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                    @endforeach
                                </flux:select>
                            </flux:field>

                            {{-- Programme Type (Full Time / Part Time — informational) --}}
                            <flux:field>
                                <flux:label for="dest_programme_type_id">Programme Type</flux:label>
                                <flux:select id="dest_programme_type_id" name="dest_programme_type_id">
                                    <option value="">— Select programme type —</option>
                                    @foreach ($programmeTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </flux:select>
                            </flux:field>

                            {{-- Programme of Study (dependent on Department + Programme) — the field actually saved --}}
                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label for="programme_of_study_id">Programme of Study <span class="text-red-500">*</span></flux:label>

                                    <div x-show="loadingProgrammeOfStudies" class="flex items-center gap-2 text-sm text-indigo-400 py-2">
                                        <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Loading…
                                    </div>

                                    <flux:select
                                        id="programme_of_study_id"
                                        name="programme_of_study_id"
                                        :invalid="$errors->has('programme_of_study_id')"
                                        x-show="!loadingProgrammeOfStudies"
                                        x-bind:disabled="programmeOfStudies.length === 0"
                                    >
                                        <option value="">
                                            <span x-text="(!departmentId || !programmeId) ? '— Select a department and programme first —' : (programmeOfStudies.length === 0 ? '— No programmes of study found —' : '— Select programme of study —')">
                                                — Select a department and programme first —
                                            </span>
                                        </option>
                                        <template x-for="pos in programmeOfStudies" :key="pos.id">
                                            <option :value="pos.id" x-text="pos.name" :selected="String(pos.id) === oldProgrammeOfStudyId"></option>
                                        </template>
                                    </flux:select>
                                    <flux:error name="programme_of_study_id" />
                                </flux:field>
                            </div>

                            {{-- Specialization (dependent on Department — informational) --}}
                            <div class="sm:col-span-2">
                                <flux:field>
                                    <flux:label for="dest_specialization_id">Specialization</flux:label>

                                    <div x-show="loadingSpecializations" class="flex items-center gap-2 text-sm text-indigo-400 py-2">
                                        <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Loading…
                                    </div>

                                    <flux:select
                                        id="dest_specialization_id"
                                        name="dest_specialization_id"
                                        x-show="!loadingSpecializations"
                                        x-bind:disabled="specializations.length === 0"
                                    >
                                        <option value="">
                                            <span x-text="!departmentId ? '— Select a department first —' : (specializations.length === 0 ? '— No specializations found —' : '— Select specialization —')">
                                                — Select a department first —
                                            </span>
                                        </option>
                                        <template x-for="spec in specializations" :key="spec.id">
                                            <option :value="spec.id" x-text="spec.name"></option>
                                        </template>
                                    </flux:select>
                                </flux:field>
                            </div>

                            <flux:field>
                                <flux:label for="level_id">Level <span class="text-red-500">*</span></flux:label>
                                <flux:select id="level_id" name="level_id" :invalid="$errors->has('level_id')">
                                    <option value="">— Select level —</option>
                                    @foreach ($levels as $id => $label)
                                        <option value="{{ $id }}" @selected(old('level_id') == $id)>{{ $label }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="level_id" />
                            </flux:field>

                            <flux:field>
                                <flux:label for="session">Academic Session <span class="text-red-500">*</span></flux:label>
                                <flux:select id="session" name="session" :invalid="$errors->has('session')">
                                    <option value="">— Select session —</option>
                                    @foreach ($sessions as $year => $label)
                                        <option value="{{ $year }}" @selected(old('session') == $year)>{{ $label }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="session" />
                            </flux:field>

                       

                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            x-bind:disabled="selected.length === 0"
                            x-bind:class="selected.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Register Selected Courses
                    </button>
                </div>
            @endif
        </form>
    @endisset

</div>

<script>
function departmentLoader(urlTemplate) {
    return {
        departments: [],
        loading: false,

        init() {
            const selectedFaculty = document.getElementById('faculty_id').value;
            if (selectedFaculty) this.loadDepartments(selectedFaculty);
        },

        loadDepartments(facultyId) {
            this.departments = [];
            if (!facultyId) return;
            this.loading = true;
            const url = urlTemplate.replace('__ID__', facultyId);
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { this.departments = data; })
                .catch(() => { this.departments = []; })
                .finally(() => { this.loading = false; });
        }
    }
}

function destinationPicker(config) {
    return {
        facultyId: '',
        departmentId: '',
        programmeId: '',
        departments: [],
        loadingDepartments: false,
        programmeOfStudies: [],
        loadingProgrammeOfStudies: false,
        specializations: [],
        loadingSpecializations: false,
        oldProgrammeOfStudyId: config.oldProgrammeOfStudyId || '',

        loadDepartments() {
            this.departments = [];
            this.departmentId = '';
            this.programmeOfStudies = [];
            this.specializations = [];
            if (!this.facultyId) return;

            this.loadingDepartments = true;
            const url = config.departmentsUrlTemplate.replace('__ID__', this.facultyId);
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { this.departments = data; })
                .catch(() => { this.departments = []; })
                .finally(() => { this.loadingDepartments = false; });
        },

        onDepartmentChange() {
            this.loadSpecializations();
            this.loadProgrammeOfStudies();
        },

        onProgrammeChange() {
            this.loadProgrammeOfStudies();
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
