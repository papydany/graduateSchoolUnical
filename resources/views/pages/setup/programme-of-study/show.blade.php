<x-layouts::app :title="$programmeOfStudy->name">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('setup.programme-of-study.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-white leading-tight">{{ $programmeOfStudy->name }}</h1>
                        <p class="text-sm text-indigo-100 mt-0.5">Programme of Study details</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('setup.programme-of-study.edit', $programmeOfStudy) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-sm font-medium text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('setup.programme-of-study.destroy', $programmeOfStudy) }}"
                          onsubmit="return confirm('Delete this programme?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/80 hover:bg-red-600 px-3 py-1.5 text-sm font-medium text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm divide-y divide-indigo-50 dark:divide-indigo-900/40">

            {{-- UUID --}}
            <div class="flex items-center gap-4 px-6 py-4 bg-indigo-50/50 dark:bg-indigo-900/10">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">UUID</span>
                <span class="font-mono text-xs tracking-wide text-zinc-500 dark:text-zinc-400 break-all">{{ $programmeOfStudy->uuid ?? '—' }}</span>
            </div>

            {{-- Programme Name --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Programme</span>
                <span class="text-sm font-bold text-zinc-800 dark:text-zinc-100">{{ $programmeOfStudy->name }}</span>
            </div>

            {{-- Degree Type --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Degree Type</span>
                @php
                    $degreeColors = [
                        1 => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        2 => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        3 => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                    ];
                    $degreeClass = $degreeColors[$programmeOfStudy->programme_id] ?? 'bg-zinc-100 text-zinc-600';
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $degreeClass }}">
                    {{ $programme->name ?? '—' }}
                </span>
            </div>

            {{-- Faculty --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Faculty</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $faculty->faculty_name ?? '—' }}</span>
            </div>

            {{-- Department --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Department</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $department->department_name ?? '—' }}</span>
            </div>

            {{-- Duration --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Duration</span>
                <div class="flex flex-wrap gap-2">
                    @php
                        $ft = $programmeOfStudy->durations->firstWhere('programme_type_id', 1);
                        $pt = $programmeOfStudy->durations->firstWhere('programme_type_id', 2);
                    @endphp
                    @if($ft)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                            Full Time: {{ $ft->name }} yr(s)
                        </span>
                    @endif
                    @if($pt)
                        <span class="inline-flex items-center rounded-full bg-teal-100 dark:bg-teal-900/40 px-3 py-1 text-xs font-semibold text-teal-700 dark:text-teal-300">
                            Part Time: {{ $pt->name }} yr(s)
                        </span>
                    @endif
                    @if(!$ft && !$pt)
                        <span class="text-sm text-zinc-400">—</span>
                    @endif
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Created</span>
                <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $programmeOfStudy->created_at ? $programmeOfStudy->created_at->format('d M Y, h:i A') : '—' }}
                </span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Last Updated</span>
                <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $programmeOfStudy->updated_at ? $programmeOfStudy->updated_at->format('d M Y, h:i A') : '—' }}
                </span>
            </div>

        </div>

    </div>
</x-layouts::app>
