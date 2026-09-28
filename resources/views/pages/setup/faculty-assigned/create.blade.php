<x-layouts::app :title="__('Assign Faculty Users')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.faculty-assigned.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-violet-200 hover:bg-violet-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Assign Faculty Users</h1>
                <p class="text-sm text-violet-100 mt-0.5">Select a faculty and a role, then the user(s) holding that role to assign.</p>
            </div>
        </div>
    </div>

    {{-- Flash / Validation Messages --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('setup.faculty-assigned.store') }}" class="flex flex-col gap-6">
        @csrf

        {{-- Faculty --}}
        <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Faculty</h2>
            </div>

            <div class="p-6">
                <flux:field>
                    <flux:label for="faculty_id">Faculty <span class="text-red-500">*</span></flux:label>
                    <flux:select id="faculty_id" name="faculty_id">
                        <option value="">— Select faculty —</option>
                        @foreach ($faculties as $fac)
                            <option value="{{ $fac->id }}" @selected(old('faculty_id') == $fac->id)>{{ $fac->faculty_name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>
        </div>

        <div x-data="{ roleId: '{{ $selectedRoleId }}' }" class="flex flex-col gap-6">

            {{-- Role --}}
            <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                    <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Role <span class="text-red-500">*</span></h2>
                </div>

                <div class="p-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach ($roles as $roleId => $roleName)
                        <label class="flex items-center gap-2 rounded-lg border px-4 py-3 text-sm cursor-pointer transition-colors"
                               :class="roleId == '{{ $roleId }}'
                                   ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/30'
                                   : 'border-zinc-200 dark:border-zinc-700 hover:bg-violet-50 dark:hover:bg-violet-900/20'">
                            <input type="radio" name="role_id" value="{{ $roleId }}" x-model="roleId"
                                   class="border-zinc-300 text-violet-600 focus:ring-violet-500">
                            <span class="font-medium text-zinc-800 dark:text-zinc-100">{{ Str::headline($roleName) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Users for the selected role --}}
            <div class="overflow-hidden rounded-xl border border-violet-100 dark:border-violet-900 bg-white dark:bg-zinc-900 shadow-sm">
                <div class="border-b border-violet-100 dark:border-violet-900 bg-violet-50/60 dark:bg-violet-900/20 px-6 py-4">
                    <h2 class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Users <span class="text-red-500">*</span></h2>
                </div>

                <div class="p-6">
                    <p x-show="!roleId" class="text-sm text-zinc-400">Select a role above to see its users.</p>

                    @foreach ($roles as $roleId => $roleName)
                        @php($users = $usersByRole->get($roleId, collect()))
                        <div x-show="roleId == '{{ $roleId }}'" x-cloak>
                            @if ($users->isEmpty())
                                <p class="text-sm text-zinc-400">No {{ Str::lower(Str::headline($roleName)) }} users exist yet.</p>
                            @else
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 max-h-64 overflow-y-auto rounded-lg border border-zinc-100 dark:border-zinc-800 p-3">
                                    @foreach ($users as $user)
                                        <label class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-violet-50 dark:hover:bg-violet-900/20 cursor-pointer">
                                            <input type="radio" name="user_id" value="{{ $user->id }}"
                                                   :disabled="roleId != '{{ $roleId }}'"
                                                   @checked(old('user_id') == $user->id)
                                                   class="border-zinc-300 text-violet-600 focus:ring-violet-500">
                                            <span class="flex flex-col">
                                                <span class="font-medium text-zinc-800 dark:text-zinc-100">{{ $user->name }}</span>
                                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 hover:bg-violet-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Assignment
            </button>
            <a href="{{ route('setup.faculty-assigned.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>
</x-layouts::app>
