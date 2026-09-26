<x-layouts::app :title="__('Edit User')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl"
     x-data="userForm(
        {{ Illuminate\Support\Js::from($roles) }},
        '{{ route('general.departments.by-faculty', ['facultyId' => '__ID__']) }}',
        '{{ old('academic_staff', $user->role?->academic_staff ?? '') }}',
        '{{ old('role_id', $user->role_id ?? '') }}',
        '{{ old('faculty_id', $user->faculty_id ?? '') }}',
        '{{ old('department_id', $user->department_id ?? '') }}'
     )">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-400 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.users.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-100 hover:bg-amber-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Edit User</h1>
                <p class="text-sm text-amber-100 mt-0.5">Update details for <strong>{{ $user->name }}</strong>.</p>
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

    <form method="POST" action="{{ route('setup.users.update', $user) }}" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-xl border border-amber-100 dark:border-amber-900/50 bg-white dark:bg-zinc-900 shadow-sm">
            {{-- Card Header --}}
            <div class="border-b border-amber-100 dark:border-amber-900/50 bg-amber-50/60 dark:bg-amber-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Account Details</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Title --}}
                    <flux:field>
                        <flux:label for="title">Title</flux:label>
                        <flux:select id="title" name="title" :invalid="$errors->has('title')">
                            <option value="">— None —</option>
                            @foreach (['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof', 'Engr', 'Barr', 'Rev'] as $t)
                                <option value="{{ $t }}" @selected(old('title', $user->title) === $t)>{{ $t }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="title" />
                    </flux:field>

                    {{-- Staff Type --}}
                    <flux:field>
                        <flux:label for="academic_staff">Staff Type <span class="text-red-500">*</span></flux:label>
                        <flux:select
                            id="academic_staff"
                            name="academic_staff"
                            :invalid="$errors->has('academic_staff')"
                            x-model="academicStaff"
                            x-on:change="onStaffTypeChange()"
                        >
                            <option value="">— Select staff type —</option>
                            <option value="1">Academic Staff</option>
                            <option value="0">Non-academic Staff</option>
                        </flux:select>
                        <flux:error name="academic_staff" />
                    </flux:field>

                    {{-- Role --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="role_id">Role <span class="text-red-500">*</span></flux:label>
                            <flux:select
                                id="role_id"
                                name="role_id"
                                :invalid="$errors->has('role_id')"
                                x-model="roleId"
                                x-bind:disabled="academicStaff === ''"
                            >
                                <option value="">
                                    <span x-text="academicStaff === '' ? '— Select a staff type first —' : '— Select role —'"></span>
                                </option>
                                <template x-for="role in filteredRoles" :key="role.id">
                                    <option :value="role.id" x-text="role.name"></option>
                                </template>
                            </flux:select>
                            <flux:error name="role_id" />
                        </flux:field>
                    </div>

                    {{-- Faculty / Department (academic staff only) --}}
                    <template x-if="academicStaff === '1'">
                        <flux:field>
                            <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                            <flux:select
                                id="faculty_id"
                                name="faculty_id"
                                :invalid="$errors->has('faculty_id')"
                                x-model="facultyId"
                                x-on:change="loadDepartments($event.target.value)"
                            >
                                <option value="">— Select faculty —</option>
                                @foreach ($faculties as $fac)
                                    <option value="{{ $fac->id }}">{{ $fac->faculty_name }}</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="faculty_id" />
                        </flux:field>
                    </template>

                    <template x-if="academicStaff === '1'">
                        <div class="sm:col-span-2">
                            <flux:field>
                                <flux:label for="department_id">Department <span class="text-red-500">*</span></flux:label>

                                <div x-show="loadingDepartments" class="flex items-center gap-2 text-sm text-amber-500 py-2">
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
                                    x-model="departmentId"
                                    x-bind:disabled="departments.length === 0"
                                >
                                    <option value="">
                                        <span x-text="departments.length === 0 ? '— Select a faculty first —' : '— Select department —'"></span>
                                    </option>
                                    <template x-for="dept in departments" :key="dept.id">
                                        <option :value="dept.id" x-text="dept.department_name"></option>
                                    </template>
                                </flux:select>

                                <flux:error name="department_id" />
                            </flux:field>
                        </div>
                    </template>

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="name">Full Name <span class="text-red-500">*</span></flux:label>
                            <flux:input
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="e.g. John Doe"
                                :invalid="$errors->has('name')"
                            />
                            <flux:error name="name" />
                        </flux:field>
                    </div>

                    {{-- Email --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="email">Email Address <span class="text-red-500">*</span></flux:label>
                            <flux:input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="e.g. john@example.com"
                                :invalid="$errors->has('email')"
                            />
                            <flux:error name="email" />
                        </flux:field>
                    </div>

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
            <a href="{{ route('setup.users.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>

<script>
function userForm(roles, departmentUrlTemplate, initialAcademicStaff, initialRoleId, initialFacultyId, initialDepartmentId) {
    return {
        roles: roles,
        academicStaff: initialAcademicStaff,
        roleId: initialRoleId,
        facultyId: initialFacultyId,
        departmentId: initialDepartmentId,
        departments: [],
        loadingDepartments: false,

        get filteredRoles() {
            if (this.academicStaff === '') return [];
            return this.roles.filter(role => String(role.academic_staff) === String(this.academicStaff));
        },

        init() {
            if (this.academicStaff === '1' && this.facultyId) {
                this.loadDepartments(this.facultyId);
            }
        },

        onStaffTypeChange() {
            this.roleId = '';

            if (this.academicStaff !== '1') {
                this.facultyId = '';
                this.departmentId = '';
                this.departments = [];
            }
        },

        loadDepartments(facultyId) {
            this.departments = [];
            this.departmentId = '';
            if (!facultyId) return;

            this.loadingDepartments = true;
            const url = departmentUrlTemplate.replace('__ID__', facultyId);

            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => {
                this.departments = data;
                if (initialDepartmentId && data.some(d => String(d.id) === String(initialDepartmentId))) {
                    this.departmentId = initialDepartmentId;
                }
            })
            .catch(() => { this.departments = []; })
            .finally(() => { this.loadingDepartments = false; });
        }
    }
}
</script>
</x-layouts::app>
