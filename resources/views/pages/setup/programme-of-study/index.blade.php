<x-layouts::app :title="__('Programmes of Study')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Programmes of Study</h1>
                <p class="mt-1 text-sm text-indigo-100">Manage graduate programmes offered by the institution.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('setup.programme-of-study.import.form') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    Import
                </a>
                <a href="{{ route('setup.programme-of-study.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-indigo-600 shadow hover:bg-indigo-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New Programme
                </a>
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

        {{-- Filters --}}
        <form method="GET" action="{{ route('setup.programme-of-study.index') }}" class="flex flex-wrap gap-3">
            <flux:input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by programme name…"
                icon="magnifying-glass"
                class="w-72"
            />
            <flux:select name="programme_id" class="w-52">
                <option value="">All Degree Types</option>
                @foreach ($programmeNames as $id => $name)
                    <option value="{{ $id }}" @selected(request('programme_id') == $id)>
                        {{ $name }}
                    </option>
                @endforeach
            </flux:select>
            <flux:button type="submit" variant="filled">Filter</flux:button>
            @if (request()->hasAny(['search', 'programme_id']))
                <flux:button href="{{ route('setup.programme-of-study.index') }}" wire:navigate>Clear</flux:button>
            @endif
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-indigo-50 dark:bg-indigo-900/40 text-left text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-300">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Programme Name</th>
                        <th class="px-4 py-3">Degree</th>
                        <th class="px-4 py-3">Faculty</th>
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50 dark:divide-indigo-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($programmes as $programme)
                        <tr class="hover:bg-indigo-50/60 dark:hover:bg-indigo-900/20 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $programmes->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $programme->name }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $degreeColors = [
                                        1 => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                        2 => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                        3 => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                                    ];
                                    $degreeClass = $degreeColors[$programme->programme_id] ?? 'bg-zinc-100 text-zinc-600';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $degreeClass }}">
                                    {{ $programmeNames[$programme->programme_id] ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-xs">{{ $faculties[$programme->faculty_id] ?? '—' }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-xs">{{ $departments[$programme->department_id] ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $ft = $programme->durations->firstWhere('programme_type_id', 1);
                                    $pt = $programme->durations->firstWhere('programme_type_id', 2);
                                @endphp
                                <div class="flex flex-wrap gap-1">
                                    @if($ft)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-300">
                                            FT: {{ $ft->name }} yr(s)
                                        </span>
                                    @endif
                                    @if($pt)
                                        <span class="inline-flex items-center rounded-full bg-teal-100 dark:bg-teal-900/40 px-2 py-0.5 text-xs font-medium text-teal-700 dark:text-teal-300">
                                            PT: {{ $pt->name }} yr(s)
                                        </span>
                                    @endif
                                    @if(!$ft && !$pt)
                                        <span class="text-zinc-400">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('setup.programme-of-study.show', $programme) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 transition-colors dark:hover:bg-indigo-900/30"
                                       title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('setup.programme-of-study.edit', $programme) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-500 hover:bg-amber-50 hover:text-amber-700 transition-colors dark:hover:bg-amber-900/30"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('setup.programme-of-study.destroy', $programme) }}"
                                          onsubmit="return confirm('Delete this programme? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors dark:hover:bg-red-900/30"
                                                title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm">No programmes found.</p>
                                    <a href="{{ route('setup.programme-of-study.create') }}" class="text-sm font-medium text-indigo-500 hover:text-indigo-700 hover:underline">
                                        Create the first one →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($programmes->hasPages())
            <div>{{ $programmes->links() }}</div>
        @endif

    </div>
</x-layouts::app>
