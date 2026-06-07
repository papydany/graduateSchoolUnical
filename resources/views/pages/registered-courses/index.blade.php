<x-layouts::app :title="__('Registered Courses')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-teal-600 to-cyan-500 px-6 py-5 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Registered Courses</h1>
                <p class="mt-1 text-sm text-teal-100">Courses registered for programmes and sessions.</p>
            </div>
            <a href="{{ route('registered-courses.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-teal-600 shadow hover:bg-teal-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Register Course
            </a>
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

        {{-- Filters --}}
        <form method="GET" action="{{ route('registered-courses.index') }}" class="flex flex-wrap gap-3">
            <flux:input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by code or title…"
                icon="magnifying-glass"
                class="w-64"
            />
            <flux:select name="programme_of_study_id" class="w-56">
                <option value="">All Programmes</option>
                @foreach ($programmeOfStudies as $id => $name)
                    <option value="{{ $id }}" @selected(request('programme_of_study_id') == $id)>{{ $name }}</option>
                @endforeach
            </flux:select>
            <flux:select name="semester" class="w-40">
                <option value="">All Semesters</option>
                <option value="1" @selected(request('semester') === '1')>1st Semester</option>
                <option value="2" @selected(request('semester') === '2')>2nd Semester</option>
            </flux:select>
            <flux:select name="status" class="w-36">
                <option value="">All Statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </flux:select>
            <flux:button type="submit" variant="filled">Filter</flux:button>
            @if (request()->hasAny(['search', 'programme_of_study_id', 'semester', 'status']))
                <flux:button href="{{ route('registered-courses.index') }}" wire:navigate>Clear</flux:button>
            @endif
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-teal-100 dark:border-teal-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-teal-50 dark:bg-teal-900/40 text-left text-xs font-semibold uppercase tracking-wider text-teal-600 dark:text-teal-300">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Programme</th>
                        <th class="px-4 py-3 text-center">Units</th>
                        <th class="px-4 py-3">Semester</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Session</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-teal-50 dark:divide-teal-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($registeredCourses as $rc)
                        <tr class="hover:bg-teal-50/60 dark:hover:bg-teal-900/20 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $registeredCourses->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs font-semibold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/40 px-2 py-0.5 rounded">
                                    {{ $rc->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-100">{{ $rc->title }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $programmeOfStudies[$rc->programme_of_study_id] ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center size-6 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                    {{ $rc->unit }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $semLabel = $rc->semester == 1 ? '1st Semester' : '2nd Semester';
                                    $semClass = $rc->semester == 1
                                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                        : 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $semClass }}">
                                    {{ $semLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">Year {{ $rc->level_id }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $rc->session }}/{{ $rc->session + 1 }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass = $rc->status === 'active'
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
                                        : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($rc->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('registered-courses.show', $rc) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-teal-500 hover:bg-teal-50 hover:text-teal-700 transition-colors dark:hover:bg-teal-900/30"
                                       title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('registered-courses.edit', $rc) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-500 hover:bg-amber-50 hover:text-amber-700 transition-colors dark:hover:bg-amber-900/30"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('registered-courses.destroy', $rc) }}"
                                          onsubmit="return confirm('Remove {{ addslashes($rc->code) }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors dark:hover:bg-red-900/30"
                                                title="Remove">
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
                            <td colspan="10" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm">No registered courses found.</p>
                                    <a href="{{ route('registered-courses.create') }}" class="text-sm font-medium text-teal-500 hover:text-teal-700 hover:underline">
                                        Register the first course →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registeredCourses->hasPages())
            <div>{{ $registeredCourses->links() }}</div>
        @endif

    </div>
</x-layouts::app>
