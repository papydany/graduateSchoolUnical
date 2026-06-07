<x-layouts::app :title="__('Add Courses')">
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
            <div>
                <h1 class="text-lg font-bold text-white">Add Courses</h1>
                <p class="text-sm text-indigo-100 mt-0.5">Create one or more courses in a single submission.</p>
            </div>
        </div>
    </div>

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

    <form method="POST" action="{{ route('courses.store') }}" class="flex flex-col gap-6">
        @csrf

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
                                <option value="{{ $fac->id }}" @selected(old('faculty_id') == $fac->id)>
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
                                    :selected="dept.id == {{ old('department_id', 'null') }}"
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
                                <option value="{{ $prog->id }}" @selected(old('programme_id') == $prog->id)>
                                    {{ $prog->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="programme_id" />
                    </flux:field>

                </div>
            </div>
        </div>

        {{-- Course Rows --}}
        <div x-data="courseRows({{ json_encode($oldRows) }})"
             class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">

            <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Course List</h2>
                    <p class="mt-0.5 text-xs text-indigo-500 dark:text-indigo-400">
                        <span x-text="rows.length"></span> course<span x-show="rows.length !== 1">s</span> to be added.
                    </p>
                </div>
                <button type="button" @click="addRow()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 text-xs font-semibold text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Row
                </button>
            </div>

            {{-- Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-3 bg-zinc-50 dark:bg-zinc-800/50 px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                <div class="col-span-2">Code <span class="text-red-400">*</span></div>
                <div class="col-span-5">Title <span class="text-red-400">*</span></div>
                <div class="col-span-2">Units <span class="text-red-400">*</span></div>
                <div class="col-span-2">Semester <span class="text-red-400">*</span></div>
                <div class="col-span-1"></div>
            </div>

            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <template x-for="(row, index) in rows" :key="row.id">
                    <div class="grid grid-cols-12 gap-3 px-6 py-4 items-start">

                        {{-- Code --}}
                        <div class="col-span-12 sm:col-span-2">
                            <label class="sm:hidden text-xs font-medium text-zinc-500 mb-1 block">Code *</label>
                            <input
                                type="text"
                                :name="`courses[${index}][code]`"
                                x-model="row.code"
                                placeholder="e.g. GSE501"
                                maxlength="20"
                                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-mono uppercase text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            />
                        </div>

                        {{-- Title --}}
                        <div class="col-span-12 sm:col-span-5">
                            <label class="sm:hidden text-xs font-medium text-zinc-500 mb-1 block">Title *</label>
                            <input
                                type="text"
                                :name="`courses[${index}][title]`"
                                x-model="row.title"
                                placeholder="e.g. Research Methodology"
                                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            />
                        </div>

                        {{-- Units --}}
                        <div class="col-span-6 sm:col-span-2">
                            <label class="sm:hidden text-xs font-medium text-zinc-500 mb-1 block">Units *</label>
                            <input
                                type="number"
                                :name="`courses[${index}][unit]`"
                                x-model="row.unit"
                                min="1"
                                max="6"
                                placeholder="2"
                                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            />
                        </div>

                        {{-- Semester --}}
                        <div class="col-span-6 sm:col-span-2">
                            <label class="sm:hidden text-xs font-medium text-zinc-500 mb-1 block">Semester *</label>
                            <select
                                :name="`courses[${index}][semester]`"
                                x-model="row.semester"
                                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            >
                                <option value="">— Select —</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                            </select>
                        </div>

                        {{-- Remove --}}
                        <div class="col-span-12 sm:col-span-1 flex sm:justify-center sm:pt-2">
                            <button type="button" @click="removeRow(index)" x-show="rows.length > 1"
                                    class="inline-flex items-center gap-1 text-xs text-red-400 hover:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="sm:hidden">Remove</span>
                            </button>
                        </div>

                    </div>
                </template>
            </div>

            {{-- Add Row Footer --}}
            <div class="border-t border-zinc-100 dark:border-zinc-800 px-6 py-3">
                <button type="button" @click="addRow()"
                        class="inline-flex items-center gap-2 text-sm font-medium text-indigo-500 hover:text-indigo-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add another course
                </button>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Courses
            </button>
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

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

function courseRows(initial) {
    return {
        rows: initial.map((r, i) => ({ ...r, id: i })),
        nextId: initial.length,

        addRow() {
            this.rows.push({ id: this.nextId++, code: '', title: '', unit: '', semester: '' });
        },

        removeRow(index) {
            if (this.rows.length > 1) this.rows.splice(index, 1);
        }
    }
}
</script>
</x-layouts::app>
