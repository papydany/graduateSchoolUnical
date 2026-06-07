<x-layouts::app :title="__('Edit Registered Course')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl"
     x-data="courseSelector(@json($courses->keyBy('id')), {{ old('course_id', $registeredCourse->course_id) }})">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-teal-600 to-cyan-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('registered-courses.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-teal-200 hover:bg-teal-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Edit Registered Course</h1>
                <p class="text-sm text-teal-100 mt-0.5">{{ $registeredCourse->code }} — {{ $registeredCourse->title }}</p>
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

    <form method="POST" action="{{ route('registered-courses.update', $registeredCourse) }}" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        {{-- Course Selection --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Select Course</h2>
                <p class="mt-0.5 text-xs text-teal-500 dark:text-teal-400">Change the course if needed. Details will auto-fill.</p>
            </div>
            <div class="p-6 flex flex-col gap-5">

                <flux:field>
                    <flux:label for="course_id">Course <span class="text-red-500">*</span></flux:label>
                    <flux:select
                        id="course_id"
                        name="course_id"
                        :invalid="$errors->has('course_id')"
                        @change="select($event.target.value)"
                    >
                        <option value="">— Select a course —</option>
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}"
                                @selected(old('course_id', $registeredCourse->course_id) == $c->id)>
                                {{ $c->code }} — {{ $c->title }}
                            </option>
                        @endforeach
                    </flux:select>
                    <flux:error name="course_id" />
                </flux:field>

                {{-- Read-only preview --}}
                <div x-show="selected" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Code</p>
                        <p class="text-sm font-mono font-semibold text-teal-600 dark:text-teal-400" x-text="selected?.code || '—'"></p>
                    </div>
                    <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3 sm:col-span-2">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Title</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100" x-text="selected?.title || '—'"></p>
                    </div>
                    <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Credit Units</p>
                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="selected?.unit || '—'"></p>
                    </div>
                    <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3 sm:col-span-2">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Semester</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-300" x-text="selected?.semester || '—'"></p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Registration Details --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Registration Details</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <flux:field>
                        <flux:label for="programme_of_study_id">Programme of Study <span class="text-red-500">*</span></flux:label>
                        <flux:select id="programme_of_study_id" name="programme_of_study_id" :invalid="$errors->has('programme_of_study_id')">
                            <option value="">— Select programme —</option>
                            @foreach ($programmeOfStudies as $pos)
                                <option value="{{ $pos->id }}"
                                    @selected(old('programme_of_study_id', $registeredCourse->programme_of_study_id) == $pos->id)>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="programme_of_study_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="level_id">Level <span class="text-red-500">*</span></flux:label>
                        <flux:select id="level_id" name="level_id" :invalid="$errors->has('level_id')">
                            <option value="">— Select level —</option>
                            @foreach ($levels as $id => $label)
                                <option value="{{ $id }}"
                                    @selected(old('level_id', $registeredCourse->level_id) == $id)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="level_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="session">Academic Session <span class="text-red-500">*</span></flux:label>
                        <flux:select id="session" name="session" :invalid="$errors->has('session')">
                            <option value="">— Select session —</option>
                            @foreach ($sessions as $year => $label)
                                <option value="{{ $year }}"
                                    @selected(old('session', $registeredCourse->session) == $year)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="session" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="status">Status <span class="text-red-500">*</span></flux:label>
                        <flux:select id="status" name="status" :invalid="$errors->has('status')">
                            <option value="active" @selected(old('status', $registeredCourse->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $registeredCourse->status) === 'inactive')>Inactive</option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
            <a href="{{ route('registered-courses.show', $registeredCourse) }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>

<script>
function courseSelector(coursesData, initialId) {
    return {
        coursesData: coursesData,
        selected: null,

        init() {
            this.$nextTick(() => {
                const val = document.getElementById('course_id')?.value || String(initialId || '');
                if (val) this.select(val);
            });
        },

        select(id) {
            this.selected = this.coursesData[id] || null;
        }
    }
}
</script>
</x-layouts::app>
