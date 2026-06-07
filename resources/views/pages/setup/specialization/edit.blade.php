<x-layouts::app :title="__('Edit Specialization')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl"
     x-data="departmentLoader(
         '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
         {{ old('department_id', $specialization->department_id) }}
     )">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-400 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.specialization.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-100 hover:bg-amber-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Edit Specialization</h1>
                <p class="text-sm text-amber-100 mt-0.5">Update details for <strong>{{ $specialization->name }}</strong>.</p>
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

    <form method="POST" action="{{ route('setup.specialization.update', $specialization) }}" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-xl border border-amber-100 dark:border-amber-900/50 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-amber-100 dark:border-amber-900/50 bg-amber-50/60 dark:bg-amber-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Specialization Details</h2>
            </div>

            <div class="p-6 flex flex-col gap-5">

                {{-- Specialization Name --}}
                <flux:field>
                    <flux:label for="name">Specialization Name <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="name"
                        name="name"
                        value="{{ old('name', $specialization->name) }}"
                        placeholder="e.g. Artificial Intelligence"
                        :invalid="$errors->has('name')"
                    />
                    <flux:error name="name" />
                </flux:field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Faculty --}}
                    <flux:field>
                        <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                        <flux:select
                            id="faculty_id"
                            name="faculty"
                            :invalid="$errors->has('faculty')"
                            x-on:change="loadDepartments($event.target.value)"
                        >
                            <option value="">— Select faculty —</option>
                            @foreach ($faculty as $fac)
                                <option value="{{ $fac->id }}"
                                    @selected(old('faculty', $specialization->faculty_id) == $fac->id)>
                                    {{ $fac->faculty_name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="faculty" />
                    </flux:field>

                    {{-- Department --}}
                    <flux:field>
                        <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                        <div x-show="loading" class="flex items-center gap-2 text-sm text-amber-400 py-2">
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
                                    :selected="dept.id == selectedDeptId"
                                ></option>
                            </template>
                        </flux:select>

                        <flux:error name="department_id" />
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
            <a href="{{ route('setup.specialization.index') }}"
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
            fetch(urlTemplate.replace('__ID__', facultyId), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => { this.departments = data; })
            .catch(() => { this.departments = []; })
            .finally(() => { this.loading = false; });
        }
    }
}
</script>
</x-layouts::app>
