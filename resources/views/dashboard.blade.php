<x-layouts::app :title="__('Dashboard')">
<div class="flex flex-1 flex-col gap-6 p-6">

    {{-- ══════════════════════════════════════════════
         WELCOME BANNER
    ══════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl bg-zinc-900 px-8 py-8 dark:bg-zinc-800">
        <div class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
                    background-size: 28px 28px;"></div>

        <div class="relative z-10 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-widest text-zinc-500">
                    {{ now()->format('l, d F Y') }}
                </p>
                <h1 class="mt-1 text-2xl font-bold text-white">
                    Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋
                </h1>
                <p class="mt-1 text-sm text-zinc-400">
                    Here's an overview of the Graduate School Portal.
                </p>
            </div>
        
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         STAT CARDS
    ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total --}}
        <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Programmes</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                    <flux:icon.academic-cap class="size-5 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalProgrammes }}</p>
                <p class="mt-0.5 text-xs text-zinc-400">Registered programmes of study</p>
            </div>
            <a href="{{ route('setup.programme-of-study.index') }}" wire:navigate
               class="mt-auto text-xs font-medium text-blue-600 hover:underline dark:text-blue-400">
                View all &rarr;
            </a>
        </div>

        {{-- Active --}}
        <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Active Programmes</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/30">
                    <flux:icon.check-circle class="size-5 text-green-600 dark:text-green-400" />
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white"></p>
                <p class="mt-0.5 text-xs text-zinc-400">
                    @if ($totalProgrammes > 0)
                        {{ round(($activeProgrammes / $totalProgrammes) * 100) }}% of all programmes
                    @else
                        No programmes yet
                    @endif
                </p>
            </div>
            <a href="{{ route('setup.programme-of-study.index') }}?status=active" wire:navigate
               class="mt-auto text-xs font-medium text-green-600 hover:underline dark:text-green-400">
                View active &rarr;
            </a>
        </div>

        {{-- Ph.D --}}
        <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Ph.D Programmes</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/30">
                    <flux:icon.book-open class="size-5 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $phdProgrammes }}</p>
                <p class="mt-0.5 text-xs text-zinc-400">Doctoral level programmes</p>
            </div>
            <span class="mt-auto text-xs text-zinc-400">Out of {{ $totalProgrammes }} total</span>
        </div>

        {{-- Inactive --}}
        <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Inactive Programmes</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/30">
                    <flux:icon.x-circle class="size-5 text-red-500 dark:text-red-400" />
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $inactiveProgrammes }}</p>
                <p class="mt-0.5 text-xs text-zinc-400">Suspended or archived</p>
            </div>
            <a href="{{ route('setup.programme-of-study.index') }}?status=inactive" wire:navigate
               class="mt-auto text-xs font-medium text-red-500 hover:underline dark:text-red-400">
                View inactive &rarr;
            </a>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════
         MAIN GRID
    ══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Recent Programmes ──────────────────── --}}
        <div class="lg:col-span-2 flex flex-col rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 px-6 py-4">
                <div>
                    <flux:heading size="base">Recent Programmes</flux:heading>
                    <flux:text class="mt-0.5 text-xs text-zinc-400">
                        Last {{ $recentProgrammes->count() }} programmes added
                    </flux:text>
                </div>
                <flux:button
                    href="{{ route('setup.programme-of-study.index') }}"
                    wire:navigate
                    size="sm"
                    variant="ghost"
                    icon-trailing="arrow-right"
                >
                    View all
                </flux:button>
            </div>

            {{-- Body --}}
            @if ($recentProgrammes->isEmpty())
                <div class="flex flex-1 flex-col items-center justify-center gap-3 py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                        <flux:icon.academic-cap class="size-7 text-zinc-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">No programmes yet</p>
                        <p class="mt-0.5 text-xs text-zinc-400">Create your first programme to get started.</p>
                    </div>
                    <flux:button
                        href="{{ route('setup.programme-of-study.create') }}"
                        wire:navigate
                        size="sm"
                        variant="primary"
                        icon="plus"
                    >
                        Create Programme
                    </flux:button>
                </div>
            @else
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($recentProgrammes as $programme)
                        <div class="flex items-center gap-4 px-6 py-3.5
                                    hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            {{-- Degree avatar --}}
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg
                                        bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold
                                        text-zinc-600 dark:text-zinc-300">
                                {{ $programme->degree_type }}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-100">
                                    {{ $programme->name }}
                                </p>
                                <p class="mt-0.5 text-xs text-zinc-400">
                                    <span class="font-mono">{{ $programme->code }}</span>
                                    &middot; {{ $programme->department }}
                                    &middot; {{ $programme->duration }} yr(s)
                                </p>
                            </div>

                            {{-- Status + Action --}}
                            <div class="flex shrink-0 items-center gap-2">
                              
                                    <flux:badge color="green" size="sm">Active</flux:badge>
                              
                                    <flux:badge color="red" size="sm">Inactive</flux:badge>
                             
                                <flux:button
                                    href="{{ route('setup.programme-of-study.edit', $programme) }}"
                                    wire:navigate
                                    size="sm"
                                    variant="ghost"
                                    icon="pencil-square"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Column ───────────────────────── --}}
        <div class="flex flex-col gap-6">

            {{-- Degree Breakdown --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 dark:border-zinc-800 px-6 py-4">
                    <flux:heading size="base">By Degree Type</flux:heading>
                    <flux:text class="mt-0.5 text-xs text-zinc-400">Programmes grouped by award</flux:text>
                </div>

                @if ($degreeBreakdown->isEmpty())
                    <p class="px-6 py-6 text-sm text-zinc-400 text-center">No data yet</p>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($degreeBreakdown as $row)
                            <div class="flex items-center gap-3 px-6 py-3">
                                <span class="w-14 shrink-0 rounded-md bg-zinc-100 dark:bg-zinc-800
                                             px-2 py-0.5 text-center font-mono text-xs font-semibold
                                             text-zinc-700 dark:text-zinc-300">
                                    {{ $row->degree_type }}
                                </span>
                                {{-- progress bar --}}
                                <div class="flex-1 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800 h-2">
                                    <div class="h-2 rounded-full bg-blue-500 dark:bg-blue-400 transition-all"
                                         style="width: {{ $totalProgrammes > 0 ? round(($row->total / $totalProgrammes) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <span class="w-5 shrink-0 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                    {{ $row->total }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 dark:border-zinc-800 px-6 py-4">
                    <flux:heading size="base">Quick Actions</flux:heading>
                </div>
                <div class="flex flex-col gap-2 p-4">

                    <a href="{{ route('setup.programme-of-study.create') }}" wire:navigate
                       class="flex items-center gap-3 rounded-lg border border-zinc-100 dark:border-zinc-800
                              bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 text-sm font-medium
                              text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800
                              transition-colors group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                    bg-blue-100 dark:bg-blue-900/40 group-hover:bg-blue-200 transition-colors">
                            <flux:icon.plus class="size-4 text-blue-600 dark:text-blue-400" />
                        </div>
                        Add Programme of Study
                    </a>

                    <a href="{{ route('setup.programme-of-study.index') }}" wire:navigate
                       class="flex items-center gap-3 rounded-lg border border-zinc-100 dark:border-zinc-800
                              bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 text-sm font-medium
                              text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800
                              transition-colors group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                    bg-green-100 dark:bg-green-900/40 group-hover:bg-green-200 transition-colors">
                            <flux:icon.list-bullet class="size-4 text-green-600 dark:text-green-400" />
                        </div>
                        Manage Programmes
                    </a>

                    <a href="{{ route('profile.edit') }}" wire:navigate
                       class="flex items-center gap-3 rounded-lg border border-zinc-100 dark:border-zinc-800
                              bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 text-sm font-medium
                              text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800
                              transition-colors group">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                    bg-zinc-200 dark:bg-zinc-700 group-hover:bg-zinc-300 transition-colors">
                            <flux:icon.cog-6-tooth class="size-4 text-zinc-600 dark:text-zinc-300" />
                        </div>
                        Account Settings
                    </a>

                </div>
            </div>

            {{-- System Info --}}
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 px-6 py-5 space-y-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">System</p>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Laravel</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">PHP</span>
                        <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}.{{ PHP_RELEASE_VERSION }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Environment</span>
                        <span class="font-mono capitalize text-zinc-700 dark:text-zinc-300">{{ app()->environment() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Logged in as</span>
                        <span class="font-mono truncate max-w-[130px] text-zinc-700 dark:text-zinc-300">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>

        </div>
        {{-- /Right Column --}}

    </div>
    {{-- /Main Grid --}}

</div>
</x-layouts::app>
