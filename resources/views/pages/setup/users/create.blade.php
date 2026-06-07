<x-layouts::app :title="__('Create User')">
<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl">

    {{-- Page Header --}}
    <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('setup.users.index') }}"
               class="inline-flex items-center justify-center rounded-lg p-1.5 text-indigo-200 hover:bg-indigo-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-white">Create User</h1>
                <p class="text-sm text-indigo-100 mt-0.5">Add a new user and assign a role.</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('setup.users.store') }}" class="flex flex-col gap-6">
        @csrf

        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900 bg-white dark:bg-zinc-900 shadow-sm">
            {{-- Card Header --}}
            <div class="border-b border-indigo-100 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-900/20 px-6 py-4">
                <h2 class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Account Details</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Title --}}
                    <flux:field>
                        <flux:label for="title">Title</flux:label>
                        <flux:select id="title" name="title" :invalid="$errors->has('title')">
                            <option value="">— None —</option>
                            @foreach (['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof', 'Engr', 'Barr', 'Rev'] as $t)
                                <option value="{{ $t }}" @selected(old('title') === $t)>{{ $t }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="title" />
                    </flux:field>

                    {{-- Role --}}
                    <flux:field>
                        <flux:label for="role_id">Role <span class="text-red-500">*</span></flux:label>
                        <flux:select id="role_id" name="role_id" :invalid="$errors->has('role_id')">
                            <option value="">— Select role —</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </flux:select>
                        <flux:error name="role_id" />
                    </flux:field>

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="name">Full Name <span class="text-red-500">*</span></flux:label>
                            <flux:input
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. John Doe"
                                :invalid="$errors->has('name')"
                            />
                            <flux:error name="name" />
                        </flux:field>
                    </div>

                    {{-- Email --}}
                    <div class="sm:col-span-2">
                        <flux:field>
                            <flux:label for="email">Email Address <span class="text-red-500">*</span></flux:label>
                            <flux:input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="e.g. john@example.com"
                                :invalid="$errors->has('email')"
                            />
                            <flux:error name="email" />
                        </flux:field>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Create User
            </button>
            <a href="{{ route('setup.users.index') }}"
               class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition-colors dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                Cancel
            </a>
        </div>
    </form>

</div>
</x-layouts::app>
