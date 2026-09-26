<x-layouts::app :title="__('Registered Course')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-teal-600 to-cyan-500 px-6 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('registered-courses.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-teal-200 hover:bg-teal-500 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-white">{{ $registeredCourse->code }}</h1>
                        <p class="text-sm text-teal-100 mt-0.5">{{ $registeredCourse->title }}</p>
                    </div>
                </div>
                <a href="{{ route('registered-courses.edit', $registeredCourse) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-teal-600 shadow hover:bg-teal-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Course Details --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Course Details</h2>
            </div>
            <dl class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Code</dt>
                    <dd class="col-span-2">
                        <span class="font-mono text-sm font-semibold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/40 px-2 py-0.5 rounded">
                            {{ $registeredCourse->code }}
                        </span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Title</dt>
                    <dd class="col-span-2 text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $registeredCourse->title }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Credit Units</dt>
                    <dd class="col-span-2">
                        <span class="inline-flex items-center justify-center size-7 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-sm font-bold text-emerald-700 dark:text-emerald-300">
                            {{ $registeredCourse->unit }}
                        </span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Semester</dt>
                    <dd class="col-span-2">
                        @php
                            $semLabel = $registeredCourse->semester == 1 ? '1st Semester' : '2nd Semester';
                            $semClass = $registeredCourse->semester == 1
                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                : 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300';
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $semClass }}">
                            {{ $semLabel }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Registration Details --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-teal-100 dark:border-teal-900 bg-teal-50/60 dark:bg-teal-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-teal-700 dark:text-teal-300 uppercase tracking-wider">Registration Details</h2>
            </div>
            <dl class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Programme</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $programme->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Programme Type</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $programmeType->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Faculty</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $faculty->faculty_name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Department</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $department->department_name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Programme of Study</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $programmeOfStudy->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Specialization</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $specialization->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Session</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">
                        {{ $registeredCourse->session }}/{{ $registeredCourse->session + 1 }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Level</dt>
                    <dd class="col-span-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $levels[$registeredCourse->level_id] ?? 'Year '.$registeredCourse->level_id }}</dd>
                </div>
                <div class="grid grid-cols-3 px-6 py-4">
                    <dt class="text-xs font-medium text-zinc-400 uppercase tracking-wider self-center">Registered On</dt>
                    <dd class="col-span-2 text-sm text-zinc-500 dark:text-zinc-400">{{ $registeredCourse->created_at->format('d M Y, g:i A') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Danger Zone --}}
        <div class="overflow-hidden rounded-xl border border-red-100 dark:border-red-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-red-100 dark:border-red-900 bg-red-50/60 dark:bg-red-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">Danger Zone</h2>
            </div>
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">Remove this registration</p>
                    <p class="text-xs text-zinc-400 mt-0.5">This will soft-delete the record. It can be restored if needed.</p>
                </div>
                <form method="POST" action="{{ route('registered-courses.destroy', $registeredCourse) }}"
                      onsubmit="return confirm('Remove {{ addslashes($registeredCourse->code) }}? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layouts::app>
