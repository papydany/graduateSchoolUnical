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
    class="border-e border-blue-900 bg-blue-950 dark:border-blue-900 dark:bg-blue-950 w-64">

    {{-- Brand --}}
    <flux:sidebar.header class="border-b border-blue-900 pb-3">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 px-1">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-900 ring-1 ring-white/10">
                <x-app-logo-icon class="size-4 fill-current text-white" />
            </div>
            <div class="grid leading-tight">
                <span class="truncate text-sm font-semibold text-white">Graduate School</span>
                <span class="truncate text-[10px] text-white/70">Portal {{ date('Y') }}</span>
            </div>
        </a>


        <flux:sidebar.collapse class="ms-auto lg:hidden text-white" />
    </flux:sidebar.header>

    {{-- Navigation --}}
    <flux:sidebar.nav class="flex-1 overflow-y-auto no-scrollbar py-4 px-2 space-y-4">

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
@if (auth()->user()->role?->name === 'admin')
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
         
    
          
                <flux:sidebar.item
                    icon="users"
                    :href="route('setup.users.index')"
                    :current="request()->routeIs('setup.users.*')"
                    wire:navigate
                >
                    Users
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="building-office"
                    :href="route('setup.department-assigned.index')"
                    :current="request()->routeIs('setup.department-assigned.*')"
                    wire:navigate
                >
                    Department Assigned
                </flux:sidebar.item>

        </flux:sidebar.group>
       @endif


        {{-- Courses --}}
        <flux:sidebar.group expandable heading="Courses" class="space-y-0.5">
           
        <flux:sidebar.item
                icon="book-open"
                :href="route('courses.index')"
                :current="request()->routeIs('courses.*')"
                wire:navigate
            >
                All Courses
            </flux:sidebar.item>

                    <flux:sidebar.item
                icon="book-open"
                :href="route('registered-courses.index')"
                :current="request()->routeIs('registered-courses.index', 'registered-courses.getCourses')"
                wire:navigate
            >
                Register Courses
            </flux:sidebar.item>

                    <flux:sidebar.item
                icon="clipboard-document-list"
                :href="route('registered-courses.list')"
                :current="request()->routeIs('registered-courses.list', 'registered-courses.show', 'registered-courses.edit')"
                wire:navigate
            >
                View Registered Courses
            </flux:sidebar.item>
        </flux:sidebar.group>

        {{-- Admissions --}}
        <flux:sidebar.group expandable heading="Admissions" class="space-y-0.5">
            <flux:sidebar.item
                icon="document-text"
                :href="route('admission.students.index')"
                :current="request()->routeIs('admission.students.*')"
                wire:navigate
            >
                Students
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

  

    {{-- Desktop user menu --}}
    <div class="border-t border-blue-900 p-2 hidden lg:block">
        <x-desktop-user-menu :name="auth()->user()->name" />
    </div>

</flux:sidebar>

{{-- ══════════════════════════════════════
     MOBILE TOP BAR
══════════════════════════════════════ --}}
<flux:header class="lg:hidden sticky top-0 z-40 border-b border-blue-900 bg-blue-950 dark:border-blue-900 dark:bg-blue-950">
    <flux:sidebar.toggle icon="bars-2" inset="left" class="text-white" />

    <span class="ms-2 text-sm font-semibold text-white">Graduate School Portal</span>

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
