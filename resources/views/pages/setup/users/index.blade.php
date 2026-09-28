<x-layouts::app :title="__('Users')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-4 py-4 sm:px-6 sm:py-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Users</h1>
                <p class="mt-1 text-sm text-indigo-100">Manage portal user accounts and role assignments.</p>
            </div>
            <a href="{{ route('setup.users.create') }}"
               class="inline-flex items-center justify-center gap-2 self-start sm:self-auto rounded-lg bg-white px-4 py-2 text-sm font-semibold text-indigo-600 shadow hover:bg-indigo-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New User
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
        <form method="GET" action="{{ route('setup.users.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap">
            <flux:input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email…"
                icon="magnifying-glass"
                class="w-full lg:w-72"
            />
            <flux:select name="role_id" class="w-full lg:w-44">
                <option value="">All Roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </flux:select>
            <flux:select name="academic_staff" class="w-full lg:w-44">
                <option value="">All Staff Types</option>
                <option value="1" @selected(request('academic_staff') === '1')>Academic Staff</option>
                <option value="0" @selected(request('academic_staff') === '0')>Non-academic Staff</option>
            </flux:select>
            <flux:select name="department_id" class="w-full lg:w-48">
                <option value="">All Departments</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </flux:select>
            <div class="flex gap-3 sm:col-span-2 lg:col-span-1">
                <flux:button type="submit" variant="filled" class="flex-1 lg:flex-none">Filter</flux:button>
                @if (request()->hasAny(['search', 'role_id', 'academic_staff', 'department_id']))
                    <flux:button href="{{ route('setup.users.index') }}" wire:navigate class="flex-1 lg:flex-none">Clear</flux:button>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl border border-indigo-100 dark:border-indigo-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-indigo-50 dark:bg-indigo-900/40 text-left text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-300">
                    <tr>
                        <th class="hidden md:table-cell px-4 py-3">#</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="hidden sm:table-cell px-4 py-3">Email</th>
                        <th class="hidden lg:table-cell px-4 py-3">Title</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="hidden md:table-cell px-4 py-3">Department</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50 dark:divide-indigo-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($users as $user)
                        <tr class="hover:bg-indigo-50/60 dark:hover:bg-indigo-900/20 transition-colors">
                            <td class="hidden md:table-cell px-4 py-3 text-zinc-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col min-w-0">
                                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $user->name }}</span>
                                    <span class="sm:hidden truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3 text-zinc-500 dark:text-zinc-400 break-all">{{ $user->email }}</td>
                            <td class="hidden lg:table-cell px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">{{ $user->title ?: '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'admin'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                                        'editor' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                        'user'   => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                                    ];
                                    $roleName  = $user->role?->name ?? 'user';
                                    $roleClass = $roleColors[$roleName] ?? $roleColors['user'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $roleClass }}">
                                    {{ ucfirst($roleName) }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                @if (($user->role?->academic_staff ?? 0) == 1)
                                    {{ $user->department?->department_name ?? '—' }}
                                @else
                                    <span class="text-zinc-300 dark:text-zinc-600">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('setup.users.show', $user) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 transition-colors dark:hover:bg-indigo-900/30"
                                       title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('setup.users.edit', $user) }}"
                                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-amber-500 hover:bg-amber-50 hover:text-amber-700 transition-colors dark:hover:bg-amber-900/30"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @unless ($user->is(auth()->user()))
                                        <form method="POST" action="{{ route('setup.users.destroy', $user) }}"
                                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
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
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm">No users found.</p>
                                    <a href="{{ route('setup.users.create') }}" class="text-sm font-medium text-indigo-500 hover:text-indigo-700 hover:underline">
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
        @if ($users->hasPages())
            <div>{{ $users->links() }}</div>
        @endif

    </div>
</x-layouts::app>
