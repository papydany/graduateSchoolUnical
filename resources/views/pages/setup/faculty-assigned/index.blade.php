<x-layouts::app :title="__('Faculty Assigned Users')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6"
         x-data="{ removal: { action: '', user: '', faculty: '' } }">

        {{-- Page Header --}}
        <div class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-5 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-white">Faculty Assigned Users</h1>
                <p class="mt-1 text-sm text-violet-100">Desk officers, faculty coordinators and transcript officers assigned to each faculty.</p>
            </div>
            <a href="{{ route('setup.faculty-assigned.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-violet-600 shadow hover:bg-violet-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Assign
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
        <form method="GET" action="{{ route('setup.faculty-assigned.index') }}" class="flex flex-wrap gap-3">
            <flux:input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by user name or email…"
                icon="magnifying-glass"
                class="w-72"
            />
            <flux:select name="faculty_id" class="w-52">
                <option value="">All Faculties</option>
                @foreach ($faculties as $id => $name)
                    <option value="{{ $id }}" @selected(request('faculty_id') == $id)>{{ $name }}</option>
                @endforeach
            </flux:select>
            <flux:select name="role_id" class="w-52">
                <option value="">All Roles</option>
                @foreach ($roles as $id => $name)
                    <option value="{{ $id }}" @selected(request('role_id') == $id)>{{ Str::headline($name) }}</option>
                @endforeach
            </flux:select>
            <flux:button type="submit" variant="filled">Filter</flux:button>
            @if (request()->hasAny(['search', 'faculty_id', 'role_id']))
                <flux:button href="{{ route('setup.faculty-assigned.index') }}" wire:navigate>Clear</flux:button>
            @endif
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-violet-50 dark:bg-violet-900/40 text-left text-xs font-semibold uppercase tracking-wider text-violet-600 dark:text-violet-300">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Faculty</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-violet-50 dark:divide-violet-900/30 bg-white dark:bg-zinc-900">
                    @forelse ($facultyAssigned as $assigned)
                        <tr class="hover:bg-violet-50/60 dark:hover:bg-violet-900/20 transition-colors">
                            <td class="px-4 py-3 text-xs text-zinc-400">{{ $facultyAssigned->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-semibold text-zinc-800 dark:text-zinc-100">{{ $assigned->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-zinc-500 dark:text-zinc-400">{{ $assigned->user?->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-zinc-500 dark:text-zinc-400">{{ $faculties[$assigned->faculty_id] ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-medium text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                    {{ $assigned->role ? Str::headline($assigned->role->name) : '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end">
                                    <button type="button"
                                            x-on:click="removal = @js([
                                                'action' => route('setup.faculty-assigned.destroy', $assigned),
                                                'user' => $assigned->user?->name ?? 'this user',
                                                'faculty' => $faculties[$assigned->faculty_id] ?? 'this faculty',
                                            ]); $flux.modal('remove-assignment').show()"
                                            class="inline-flex items-center justify-center rounded-lg p-1.5 text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors dark:hover:bg-red-900/30"
                                            title="Remove from faculty">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                    </svg>
                                    @if (request()->hasAny(['search', 'faculty_id', 'role_id']))
                                        <p class="text-sm">No faculty assignments match your filters.</p>
                                    @else
                                        <p class="text-sm">No users have been assigned to a faculty yet.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($facultyAssigned->hasPages())
            <div>{{ $facultyAssigned->links() }}</div>
        @endif

        {{-- Remove Confirmation Modal --}}
        <flux:modal name="remove-assignment" class="md:w-96">
            <form method="POST" x-bind:action="removal.action" class="space-y-6">
                @csrf
                @method('DELETE')

                <div class="flex items-start gap-4">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="lg">Remove from faculty?</flux:heading>
                        <flux:text class="mt-2">
                            <span class="font-semibold text-zinc-800 dark:text-zinc-100" x-text="removal.user"></span>
                            will no longer be assigned to
                            <span class="font-semibold text-zinc-800 dark:text-zinc-100" x-text="removal.faculty"></span>.
                        </flux:text>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger">Remove</flux:button>
                </div>
            </form>
        </flux:modal>

    </div>
</x-layouts::app>
