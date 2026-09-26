<x-layouts::app :title="$user->name">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('setup.users.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-white leading-tight">{{ $user->name }}</h1>
                        <p class="text-sm text-indigo-100 mt-0.5">User details</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('setup.users.edit', $user) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-sm font-medium text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    @unless ($user->is(auth()->user()))
                        <form method="POST" action="{{ route('setup.users.destroy', $user) }}"
                              onsubmit="return confirm('Delete this user?')">
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
                    @endunless
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm divide-y divide-indigo-50 dark:divide-indigo-900/40">

            {{-- Avatar + Name --}}
            <div class="flex items-center gap-4 px-6 py-5 bg-indigo-50/50 dark:bg-indigo-900/10">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-lg font-bold text-indigo-600 dark:text-indigo-300">
                    {{ $user->initials() }}
                </div>
                <div>
                    <p class="font-bold text-zinc-800 dark:text-zinc-100 text-base">
                        {{ $user->title ? $user->title . ' ' : '' }}{{ $user->name }}
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                </div>
            </div>

            {{-- UUID --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">UUID</span>
                <span class="font-mono text-xs tracking-wide text-zinc-500 dark:text-zinc-400 break-all">{{ $user->uuid ?? '—' }}</span>
            </div>

            {{-- Title --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Title</span>
                <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $user->title ?: '—' }}</span>
            </div>

            {{-- Role --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Role</span>
                @php
                    $roleColors = [
                        'admin'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                        'editor' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                        'user'   => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                    ];
                    $roleName  = $user->role?->name ?? 'user';
                    $roleClass = $roleColors[$roleName] ?? $roleColors['user'];
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                    {{ ucfirst($roleName) }}
                </span>
            </div>

            {{-- Staff Type --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Staff Type</span>
                @if (($user->role?->academic_staff ?? null) === null)
                    <span class="text-sm text-zinc-400">—</span>
                @elseif ((int) $user->role->academic_staff === 1)
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300">
                        Academic Staff
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">
                        Non-academic Staff
                    </span>
                @endif
            </div>

            {{-- Faculty & Department (academic staff only) --}}
            @if ((int) ($user->role?->academic_staff ?? 0) === 1)
                <div class="flex items-center gap-4 px-6 py-4">
                    <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Faculty</span>
                    <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $user->faculty?->faculty_name ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-4 px-6 py-4">
                    <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Department</span>
                    <span class="text-sm text-zinc-700 dark:text-zinc-200">{{ $user->department?->department_name ?? '—' }}</span>
                </div>
            @endif

            {{-- Email Verified --}}
            <div class="flex items-center gap-4 px-6 py-4">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Email Verified</span>
                @if ($user->email_verified_at)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Verified — {{ $user->email_verified_at->format('d M Y') }}
                    </span>
                @else
                    <span class="text-xs font-medium text-zinc-400">Not verified</span>
                @endif
            </div>

            {{-- Timestamps --}}
            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Created</span>
                <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $user->created_at?->format('d M Y, h:i A') ?? '—' }}
                </span>
            </div>

            <div class="flex items-center gap-4 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/30">
                <span class="w-32 shrink-0 text-xs font-semibold uppercase tracking-wider text-indigo-400 dark:text-indigo-500">Last Updated</span>
                <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $user->updated_at?->format('d M Y, h:i A') ?? '—' }}
                </span>
            </div>

        </div>

    </div>
</x-layouts::app>
