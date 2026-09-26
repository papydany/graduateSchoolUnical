<x-layouts::app :title="__('Import Programmes of Study')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl"
     x-data="programmeImportForm({
         departmentsUrlTemplate: '{{ route('setup.programme-of-study.departments', ['facultyId' => '__ID__']) }}',
         oldFaculty: '{{ old('faculty_id') }}',
         oldDepartment: '{{ old('department_id') }}',
     })">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.programme-of-study.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Import Programmes of Study</h1>
                <p class="text-sm text-indigo-100 mt-0.5">Bulk-create programmes of study from an Excel file.</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Download Sample Template --}}
    <div class="flex items-center justify-between rounded-xl border border-emerald-100 dark:border-emerald-900 bg-emerald-50/60 dark:bg-emerald-900/20 px-6 py-4">
        <div>
            <h2 class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Need the template?</h2>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">
                Download an empty Excel file with the correct header, then list one programme of study name per row.
            </p>
        </div>
        <a href="{{ route('setup.programme-of-study.sample') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow transition-colors shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-6l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download Sample Excel Template
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
        <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
            <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">File Format</h2>
        </div>
        <div class="p-6 text-sm text-zinc-600 dark:text-zinc-400 space-y-3">
            <p>Upload an <strong>.xlsx</strong> or <strong>.xls</strong> file with a header row containing this column:</p>
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 text-left">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Programme of Study Name</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        <tr>
                            <td class="px-3 py-2">Computer Science</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p>
                The Programme, Faculty, Department and Duration selected below are applied to
                <strong>every row</strong> in the file. Rows matching an existing programme of study
                (same name, faculty, department and programme) are skipped as duplicates.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('setup.programme-of-study.import') }}" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf

        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Import Context</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Programme Type --}}
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

                    {{-- Faculty --}}
                    <flux:field>
                        <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                        <flux:select
                            id="faculty_id"
                            name="faculty_id"
                            :invalid="$errors->has('faculty_id')"
                            x-model="facultyId"
                            x-on:change="loadDepartments()"
                        >
                            <option value="">— Select faculty —</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="faculty_id" />
                    </flux:field>

                    {{-- Department (dependent) --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                            <div x-show="loadingDepartments" class="flex items-center gap-2 text-sm text-indigo-400 py-2">
                                <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Loading departments…
                            </div>

                            <flux:select
                                id="department_id"
                                name="department_id"
                                :invalid="$errors->has('department_id')"
                                x-show="!loadingDepartments"
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
                                        :selected="String(dept.id) === oldDepartment"
                                    ></option>
                                </template>
                            </flux:select>

                            <flux:error name="department_id" />
                        </flux:field>
                    </div>

{{-- Duration Full Time --}}
                    <flux:field>
                        <flux:label for="duration_full_time">
                            Duration — Full Time (yrs) <span class="text-red-500">*</span>
                        </flux:label>
                        <flux:input
                            id="duration_full_time"
                            name="duration_full_time"
                            type="number"
                            min="1"
                            max="4"
                            value="{{ old('duration_full_time') }}"
                            placeholder="e.g. 2"
                            :invalid="$errors->has('duration_full_time')"
                        />
                        <flux:error name="duration_full_time" />
                    </flux:field>

                    {{-- Duration Part Time --}}
                    <flux:field>
                        <flux:label for="duration_part_time">Duration — Part Time (yrs)</flux:label>
                        <flux:input
                            id="duration_part_time"
                            name="duration_part_time"
                            type="number"
                            min="1"
                            max="6"
                            value="{{ old('duration_part_time') }}"
                            placeholder="e.g. 3"
                            :invalid="$errors->has('duration_part_time')"
                        />
                        <flux:error name="duration_part_time" />
                    </flux:field>


                
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Upload File</h2>
            </div>
            <div class="p-6">
                <flux:field>
                    <flux:label for="file">Excel File <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="file"
                        name="file"
                        type="file"
                        accept=".xlsx,.xls"
                        required
                        :invalid="$errors->has('file')"
                    />
                    <flux:error name="file" />
                </flux:field>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Upload &amp; Import
            </button>
            <a href="{{ route('setup.programme-of-study.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>

<script>
function programmeImportForm(config) {
    return {
        departments: [],
        loadingDepartments: false,
        facultyId: config.oldFaculty || '',
        oldDepartment: config.oldDepartment || '',

        init() {
            if (this.facultyId) {
                this.loadDepartments();
            }
        },

        loadDepartments() {
            this.departments = [];
            if (!this.facultyId) return;

            this.loadingDepartments = true;
            const url = config.departmentsUrlTemplate.replace('__ID__', this.facultyId);

            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => { this.departments = data; })
            .catch(() => { this.departments = []; })
            .finally(() => { this.loadingDepartments = false; });
        }
    }
}
</script>
</x-layouts::app>
