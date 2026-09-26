<x-layouts::app :title="__('Import Specializations')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl"
     x-data="specializationImportForm({
         departmentsUrlTemplate: '{{ route('setup.specialization.departments', ['facultyId' => '__ID__']) }}',
         oldFaculty: '{{ old('faculty_id') }}',
         oldDepartment: '{{ old('department_id') }}',
     })">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.specialization.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-violet-200 hover:bg-violet-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Import Specializations</h1>
                <p class="text-sm text-violet-100 mt-0.5">Bulk-create specializations from an Excel file.</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif
    @if (session('import_errors') && count(session('import_errors')) > 0)
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
            <p class="font-semibold mb-1">{{ count(session('import_errors')) }} row(s) had issues:</p>
            <ul class="list-disc list-inside space-y-0.5 max-h-40 overflow-y-auto">
                @foreach (session('import_errors') as $importError)
                    <li>{{ $importError }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Download Sample Template --}}
    <div class="flex items-center justify-between rounded-xl border border-emerald-100 dark:border-emerald-900 bg-emerald-50/60 dark:bg-emerald-900/20 px-6 py-4">
        <div>
            <h2 class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Need the template?</h2>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">
                Download an Excel file with the correct header, then list one specialization name per row.
            </p>
        </div>
        <a href="{{ route('setup.specialization.template') }}"
           class="inline-flex items-center gap-2 rounded-md bg-green-600 hover:bg-green-700 px-4 py-2.5 text-sm font-semibold text-white shadow transition-colors shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-6l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download Sample Template
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
        <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
            <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">File Format</h2>
        </div>
        <div class="p-6 text-sm text-zinc-600 dark:text-zinc-400 space-y-3">
            <p>Upload an <strong>.xlsx</strong> or <strong>.xls</strong> file with a header row containing this column:</p>
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 text-left">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Specialization Name</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        <tr><td class="px-3 py-2">Software Engineering</td></tr>
                        <tr><td class="px-3 py-2">Computer Networks</td></tr>
                        <tr><td class="px-3 py-2">Artificial Intelligence</td></tr>
                    </tbody>
                </table>
            </div>
            <p>
                The Faculty and Department selected below are applied to <strong>every row</strong> in the file.
                Rows with a specialization name already used in that department are skipped as duplicates.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('setup.specialization.import') }}" enctype="multipart/form-data"
          class="flex flex-col gap-6" x-on:submit="submitting = true">
        @csrf

        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Import Context</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

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
                    <flux:field>
                        <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                        <div x-show="loadingDepartments" class="flex items-center gap-2 text-sm text-violet-400 py-2">
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
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Upload File</h2>
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
                    x-bind:disabled="submitting"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 hover:bg-violet-700 disabled:opacity-60 disabled:cursor-not-allowed px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg x-show="!submitting" xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                <svg x-show="submitting" class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-text="submitting ? 'Importing…' : 'Upload & Import'"></span>
            </button>
            <a href="{{ route('setup.specialization.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>

<script>
function specializationImportForm(config) {
    return {
        departments: [],
        loadingDepartments: false,
        submitting: false,
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
