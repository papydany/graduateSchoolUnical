<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-100 dark:bg-zinc-950">

{{-- ══════════════════════════════════════
     SIDEBAR
══════════════════════════════════════ --}}
<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 w-64">

    {{-- Brand --}}
    <flux:sidebar.header class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 px-1">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-900 dark:bg-white/10 ring-1 ring-zinc-800/10 dark:ring-white/10">
                <x-app-logo-icon class="size-4 fill-current text-white" />
            </div>
            <div class="grid leading-tight">
                <span class="truncate text-sm font-semibold text-zinc-900 dark:text-white">Graduate School</span>
                <span class="truncate text-[10px] text-zinc-400 dark:text-zinc-500">Portal {{ date('Y') }}</span>
            </div>
        </a>
        <flux:sidebar.collapse class="ms-auto lg:hidden text-zinc-400" />
    </flux:sidebar.header>

    {{-- Navigation --}}
    <flux:sidebar.nav class="flex-1 overflow-y-auto py-4 px-2 space-y-4">

        {{-- Platform --}}
        <flux:sidebar.group heading="Platform" class="space-y-0.5">
            <flux:sidebar.item
                icon="home"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                wire:navigate
            >
                Dashboard
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- Setup --}}
        <flux:sidebar.group expandable heading="Setup" class="space-y-0.5">
            <flux:sidebar.item
                icon="academic-cap"
                :href="route('setup.programme-of-study.index')"
                :current="request()->routeIs('setup.programme-of-study.*')"
                wire:navigate
            >
                Programmes of Study
            </flux:sidebar.item>
            <flux:sidebar.item icon="building-library" href="{{ route('setup.specialization.index') }}" :current="request()->routeIs('setup.specialization.*')">
                Specialization
            </flux:sidebar.item>
            <flux:sidebar.item icon="rectangle-stack" href="#" :current="false">
                Sessions &amp; Semesters
            </flux:sidebar.item>
            @if (auth()->user()->role?->name === 'admin')
                <flux:sidebar.item
                    icon="users"
                    :href="route('setup.users.index')"
                    :current="request()->routeIs('setup.users.*')"
                    wire:navigate
                >
                    Users
                </flux:sidebar.item>
            @endif
        </flux:sidebar.group>

        {{-- Admissions --}}
        <flux:sidebar.group expandable heading="Admissions" class="space-y-0.5">
            <flux:sidebar.item icon="document-text" href="#" :current="false">
                Applications
            </flux:sidebar.item>
            <flux:sidebar.item icon="check-badge" href="#" :current="false">
                Offer Letters
            </flux:sidebar.item>
            <flux:sidebar.item icon="arrow-down-tray" href="#" :current="false">
                Enrolments
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- Students --}}
        <flux:sidebar.group expandable heading="Students" class="space-y-0.5">
            <flux:sidebar.item icon="user-group" href="#" :current="false">
                All Students
            </flux:sidebar.item>
            <flux:sidebar.item icon="clipboard-document-list" href="#" :current="false">
                Course Registration
            </flux:sidebar.item>
            <flux:sidebar.item icon="book-open" href="#" :current="false">
                Results
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- Reports --}}
        <flux:sidebar.group expandable heading="Reports" class="space-y-0.5">
            <flux:sidebar.item icon="chart-bar" href="#" :current="false">
                Statistics
            </flux:sidebar.item>
            <flux:sidebar.item icon="printer" href="#" :current="false">
                Print Centre
            </flux:sidebar.item>
        </flux:sidebar.group>

    </flux:sidebar.nav>

    <flux:spacer />

    {{-- Desktop user menu --}}
    <div class="border-t border-zinc-100 dark:border-zinc-800 p-2 hidden lg:block">
        <x-desktop-user-menu :name="auth()->user()->name" />
    </div>

</flux:sidebar>

{{-- ══════════════════════════════════════
     MOBILE TOP BAR
══════════════════════════════════════ --}}
<flux:header class="lg:hidden sticky top-0 z-40 border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
    <flux:sidebar.toggle icon="bars-2" inset="left" class="text-zinc-500" />

    <span class="ms-2 text-sm font-semibold text-zinc-800 dark:text-white">Graduate School Portal</span>

    <flux:spacer />

    <flux:dropdown position="bottom" align="end">
        <flux:profile
            :initials="auth()->user()->initials()"
            icon-trailing="chevron-down"
        />
        <flux:menu>
            <div class="flex items-center gap-2 px-2 py-2">
                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                <div class="grid leading-tight">
                    <span class="truncate text-sm font-medium text-zinc-800 dark:text-white">{{ auth()->user()->name }}</span>
                    <span class="truncate text-xs text-zinc-400">{{ auth()->user()->email }}</span>
                </div>
            </div>
            <flux:menu.separator />
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
            <flux:menu.separator />
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer" data-test="logout-button">
                    Log out
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

{{-- ══════════════════════════════════════
     PAGE SLOT
══════════════════════════════════════ --}}
{{ $slot }}

@persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
@endpersist

@fluxScripts
</body>
</html>
