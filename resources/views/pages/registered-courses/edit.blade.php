<x-layouts::app :title="__('Edit Registered Course')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl">

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

        {{-- Registration Details (read-only) --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Registration Details</h2>
                <p class="mt-0.5 text-xs text-teal-500 dark:text-teal-400">These were set at registration and can't be changed here.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-3">
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Code</p>
                    <p class="text-sm font-mono font-semibold text-teal-600 dark:text-teal-400">{{ $registeredCourse->code }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Semester</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ $registeredCourse->semester == 1 ? '1st Semester' : '2nd Semester' }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Session</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ $registeredCourse->session }}/{{ $registeredCourse->session + 1 }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Level</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ $levels[$registeredCourse->level_id] ?? 'Year '.$registeredCourse->level_id }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3 sm:col-span-2">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Programme of Study</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ optional($programmeOfStudies->firstWhere('id', $registeredCourse->programme_of_study_id))->name ?? '—' }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Specialization</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ optional($specializations->firstWhere('id', $registeredCourse->specialization_id))->name ?? '—' }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Programme Type</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ optional($programmeTypes->firstWhere('id', $registeredCourse->programme_type_id))->name ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Editable Fields --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Course Details</h2>
                <p class="mt-0.5 text-xs text-teal-500 dark:text-teal-400">Only the title and credit unit can be edited.</p>
            </div>
            <div class="p-6 flex flex-col gap-5">

                <flux:field>
                    <flux:label for="title">Title <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="title"
                        name="title"
                        value="{{ old('title', $registeredCourse->title) }}"
                        :invalid="$errors->has('title')"
                    />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label for="unit">Credit Unit <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="unit"
                        name="unit"
                        type="number"
                        min="1"
                        max="6"
                        value="{{ old('unit', $registeredCourse->unit) }}"
                        :invalid="$errors->has('unit')"
                    />
                    <flux:error name="unit" />
                </flux:field>

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
</x-layouts::app>
