<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-zinc-950">

<div class="grid min-h-screen lg:grid-cols-2">

    {{-- ═══════════════════════════════════════════════
         LEFT PANEL — Institution Branding
    ════════════════════════════════════════════════ --}}
    <div class="relative hidden lg:flex flex-col justify-between bg-blue-950 p-12 overflow-hidden">

        {{-- Background texture --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 opacity-90"></div>
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;">
        </div>

        {{-- Top: Logo + Name --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 ring-1 ring-white/20">
                <x-app-logo-icon class="size-6 fill-current text-white" />
            </div>
            <span class="text-lg font-semibold text-white">{{ config('app.name', 'Graduate School Portal') }}</span>
        </div>

        {{-- Centre: Hero copy --}}
        <div class="relative z-10 space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-medium uppercase tracking-widest text-white/70">
                Academic Excellence
            </div>
            <h1 class="text-4xl font-bold leading-tight text-white">
                Advancing Knowledge,<br>
                <span class="text-white/60">Shaping Tomorrow.</span>
            </h1>
            <p class="max-w-sm text-base leading-relaxed text-white/50">
                The Graduate School Portal gives postgraduate students, supervisors
                and administrators a unified platform to manage admissions,
                programmes and academic records.
            </p>
        </div>

        {{-- Bottom: Stats --}}
        <div class="relative z-10 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
            <div>
                <p class="text-2xl font-bold text-white">50+</p>
                <p class="mt-0.5 text-xs text-white/40">Programmes of Study</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">12</p>
                <p class="mt-0.5 text-xs text-white/40">Faculties</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">5k+</p>
                <p class="mt-0.5 text-xs text-white/40">Enrolled Students</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         RIGHT PANEL — Login Form
    ════════════════════════════════════════════════ --}}
    <div class="flex flex-col items-center justify-center px-6 py-12 sm:px-12 lg:px-16">

        {{-- Mobile logo (hidden on large screens) --}}
        <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2 lg:hidden" wire:navigate>
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-900 dark:bg-blue-900/40">
                <x-app-logo-icon class="size-5 fill-current text-white" />
            </div>
            <span class="text-base font-semibold text-zinc-800 dark:text-white">
                {{ config('app.name', 'Graduate School Portal') }}
            </span>
        </a>

        <div class="w-full max-w-sm space-y-8">

            {{-- Heading --}}
            <div class="space-y-1">
                <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                    Check Registration
                </flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400">
                    Enter your registration number to find your student record.
                </flux:text>
            </div>

            {{-- Result --}}
            @if (session('student'))
                @php($found = session('student'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 dark:border-green-800/40 dark:bg-green-900/20">
                    <p class="text-sm font-medium text-green-800 dark:text-green-400">
                        {{ $found['surname'] }} {{ $found['firstname'] }} {{ $found['othername'] }}
                    </p>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-500">
                        Matriculation Number: <span class="font-semibold">{{ $found['matriculation_number'] }}</span>
                    </p>
                    <p class="mt-2 text-sm text-green-700 dark:text-green-500">
                        Your profile is already complete.
                        <a href="{{ route('student.login') }}" class="font-semibold underline">Log in here</a>.
                    </p>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('student.profile.store') }}" class="space-y-5">
                @csrf

                <flux:field>
                    <flux:label for="registration_number">Registration Number</flux:label>
                    <flux:input
                        id="registration_number"
                        name="registration_number"
                        type="text"
                        :value="old('registration_number')"
                        placeholder="Enter your registration number"
                        required
                        autofocus
                        :invalid="$errors->has('registration_number')"
                    />
                    <flux:error name="registration_number" />
                </flux:field>

                <flux:button
                    type="submit"
                    variant="primary"
                    class="w-full bg-blue-900! hover:bg-blue-950! text-white!"
                    data-test="check-registration-button"
                >
                    Submit
                </flux:button>
            </form>

            <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">
                Already have your matriculation number?
                <a href="{{ route('student.login') }}" class="font-medium text-blue-900 hover:underline dark:text-blue-400">
                    Student login
                </a>
            </p>

            {{-- Footer --}}
            <p class="text-center text-xs text-zinc-400 dark:text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name', '') }}.
                All rights reserved.
            </p>

        </div>
    </div>

</div>

@persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
@endpersist

@fluxScripts
</body>
</html>
