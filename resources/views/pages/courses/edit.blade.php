<x-layouts::app :title="__('Edit Course')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl"
     x-data="departmentLoader(
         '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
         {{ old('department_id', $course->department_id) }}
     )">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-400 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-100 hover:bg-amber-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Edit Course</h1>
                <p class="text-sm text-amber-100 mt-0.5">Update details for <strong>{{ $course->code }}</strong>.</p>
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

    <form method="POST" action="{{ route('courses.update', $course) }}" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-xl border border-amber-100 dark:border-amber-900/50 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-amber-100 dark:border-amber-900/50 bg-amber-50/60 dark:bg-amber-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Course Details</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Code --}}
                    <flux:field>
                        <flux:label for="code">Course Code <span class="text-red-500">*</span></flux:label>
                        <flux:input
                            id="code"
                            name="code"
                            value="{{ old('code', $course->code) }}"
                            placeholder="e.g. GSE501"
                            class="font-mono uppercase"
                            :invalid="$errors->has('code')"
                            readonly
                        />
                        <flux:error name="code" />
                    </flux:field>

                    {{-- Units --}}
                    <flux:field>
                        <flux:label for="unit">Credit Units <span class="text-red-500">*</span></flux:label>
                        <flux:input
                            id="unit"
                            name="unit"
                            type="number"
                            min="1"
                            max="6"
                            value="{{ old('unit', $course->unit) }}"
                            placeholder="e.g. 2"
                            :invalid="$errors->has('unit')"
                        />
                        <flux:error name="unit" />
                    </flux:field>

                    {{-- Title --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="title">Course Title <span class="text-red-500">*</span></flux:label>
                            <flux:input
                                id="title"
                                name="title"
                                value="{{ old('title', $course->title) }}"
                                placeholder="e.g. Research Methodology"
                                :invalid="$errors->has('title')"
                            />
                            <flux:error name="title" />
                        </flux:field>
                    </div>

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
                                <option value="{{ $fac->id }}" @selected(old('faculty_id', $course->faculty_id) == $fac->id)>
                                    {{ $fac->faculty_name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="faculty_id" />
                    </flux:field>

                    {{-- Department --}}
                    <flux:field>
                        <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                        <div x-show="loading" class="flex items-center gap-2 text-sm text-amber-400 py-2">
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
                            <option value="">— Select department —</option>
                            <template x-for="dept in departments" :key="dept.id">
                                <option
                                    :value="dept.id"
                                    x-text="dept.department_name"
                                    :selected="dept.id == selectedDeptId"
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
                                <option value="{{ $prog->id }}" @selected(old('programme_id', $course->programme_id) == $prog->id)>
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
                            <option value="">— Select —</option>
                            <option value="1" @selected(old('semester', $course->semester) === 1)>1st Semester</option>
                            <option value="2" @selected(old('semester', $course->semester) === 2)>2nd Semester</option>
                        </flux:select>
                        <flux:error name="semester" />
                    </flux:field>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>

<script>
function departmentLoader(urlTemplate, selectedDeptId) {
    return {
        departments: [],
        loading: false,
        selectedDeptId: selectedDeptId,

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
</script>
</x-layouts::app>
