<x-layouts::app :title="$course->code">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-white leading-tight">{{ $course->code }}</h1>
                        <p class="text-sm text-indigo-100 mt-0.5">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('registered-courses.index', ['course_id' => $course->id]) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-sm font-medium text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Registered Courses
                    </a>
                    <a href="{{ route('courses.edit', $course) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-sm font-medium text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm divide-y divide-indigo-50 dark:divide-indigo-900/40">

            <div class="flex items-center gap-4 px-6 py-4 bg-indigo-50/50 dark:bg-indigo-900/10">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">UUID</span>
                <span class="font-mono text-xs tracking-wide text-zinc-500 break-all">{{ $course->uuid ?? '—' }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Code</span>
                <span class="font-mono text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 px-2.5 py-1 rounded">
                    {{ $course->code }}
                </span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Title</span>
                <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ $course->title }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Faculty</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $faculty->faculty_name ?? '—' }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Department</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $department->department_name ?? '—' }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Programme</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $programme->name ?? '—' }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Credit Units</span>
                <span class="inline-flex items-center justify-center size-7 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-sm font-bold text-emerald-700 dark:text-emerald-300">
                    {{ $course->unit }}
                </span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Semester</span>
                @php
                    $semClass = $course->semester === '1st Semester'
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                        : 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300';
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $semClass }}">
                    {{ $course->semester }}
                </span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Created</span>
                <span class="text-sm text-zinc-500">{{ $course->created_at?->format('d M Y, h:i A') ?? '—' }}</span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400">Last Updated</span>
                <span class="text-sm text-zinc-500">{{ $course->updated_at?->format('d M Y, h:i A') ?? '—' }}</span>
            </div>

        </div>

    </div>
</x-layouts::app>
