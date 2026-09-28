<x-layouts::app :title="__('Students')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-5 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Students</h1>
                <p class="mt-1 text-sm text-violet-100">Newly admitted students uploaded from Excel.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admission.students.template') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-white/40 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-6l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Template
                </a>
                <a href="{{ route('admission.students.import.form') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-violet-600 shadow hover:bg-violet-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    Import Students
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
        <form method="GET" action="{{ route('admission.students.index') }}" class="flex flex-wrap gap-3">
            <flux:input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or registration number…"
                icon="magnifying-glass"
                class="w-72"
            />
            <flux:select name="faculty_id" class="w-52">
                <option value="">All Faculties</option>
                @foreach ($faculties as $id => $name)
                    <option value="{{ $id }}" @selected(request('faculty_id') == $id)>{{ $name }}</option>
                @endforeach
            </flux:select>
            <flux:select name="programme_id" class="w-40">
                <option value="">All Programmes</option>
                @foreach ($programmes as $id => $name)
                    <option value="{{ $id }}" @selected(request('programme_id') == $id)>{{ $name }}</option>
                @endforeach
            </flux:select>
            <flux:select name="entry_session" class="w-40">
                <option value="">All Sessions</option>
                @foreach ($sessions as $year => $label)
                    <option value="{{ $year }}" @selected(request('entry_session') == $year)>{{ $label }}</option>
                @endforeach
            </flux:select>
            <flux:button type="submit" variant="filled">Filter</flux:button>
            @if (request()->hasAny(['search', 'faculty_id', 'programme_id', 'entry_session']))
                <flux:button href="{{ route('admission.students.index') }}" wire:navigate>Clear</flux:button>
            @endif
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-violet-50 dark:bg-violet-900/40 text-left text-xs font-semibold uppercase tracking-wider text-violet-600 dark:text-violet-300">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Reg. Number</th>
                        <th class="px-4 py-3">Surname</th>
                        <th class="px-4 py-3">Firstname</th>
                        <th class="px-4 py-3">Othername</th>
                        <th class="px-4 py-3">Faculty</th>
                        <th class="px-4 py-3">Dept</th>
                        <th class="px-4 py-3">Programme</th>
                        <th class="px-4 py-3">Entry Session</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-violet-50 dark:divide-violet-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($students as $student)
                        <tr class="hover:bg-violet-50/60 dark:hover:bg-violet-900/20 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $students->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/40 px-2 py-0.5 rounded">
                                    {{ $student->registration_number }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-100">{{ $student->surname }}</td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-200">{{ $student->firstname }}</td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-200">{{ $student->othername ?? '—' }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $faculties[$student->faculty_id] ?? '—' }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $departments[$student->department_id] ?? '—' }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $programmes[$student->programme_id] ?? '—' }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $student->entry_session }}/{{ $student->entry_session + 1 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm">No students found.</p>
                                    <a href="{{ route('admission.students.import.form') }}" class="text-sm font-medium text-violet-500 hover:text-violet-700 hover:underline">
                                        Import the first batch →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($students->hasPages())
            <div>{{ $students->links() }}</div>
        @endif

    </div>
</x-layouts::app>
