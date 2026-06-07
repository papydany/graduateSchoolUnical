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
    <div class="relative hidden lg:flex flex-col justify-between bg-zinc-900 p-12 overflow-hidden">

        {{-- Background texture --}}
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950 opacity-90"></div>
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
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-900 dark:bg-white/10">
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
                    Welcome back
                </flux:heading>
                <flux:text class="text-zinc-500 dark:text-zinc-400">
                    Sign in to your portal account to continue.
                </flux:text>
            </div>

            {{-- Session status (e.g. password reset success) --}}
            <x-auth-session-status :status="session('status')" />

            {{-- Validation errors summary --}}
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800/40 dark:bg-red-900/20">
                    <div class="flex items-start gap-2">
                        <flux:icon.exclamation-triangle class="mt-0.5 size-4 shrink-0 text-red-500" />
                        <div>
                            <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                Please fix the following errors:
                            </p>
                            <ul class="mt-1 list-disc list-inside space-y-0.5 text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <flux:field>
                    <flux:label for="email">Email Address</flux:label>
                    <flux:input
                        id="email"
                        name="email"
                        type="email"
                        :value="old('email')"
                        placeholder="you@institution.edu.ng"
                        required
                        autofocus
                        autocomplete="email"
                        :invalid="$errors->has('email')"
                    />
                    <flux:error name="email" />
                </flux:field>

                {{-- Password --}}
                <flux:field>
                    <div class="flex items-center justify-between">
                        <flux:label for="password">Password</flux:label>
                        @if (Route::has('password.request'))
                            <flux:link
                                :href="route('password.request')"
                                wire:navigate
                                class="text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300"
                            >
                                Forgot password?
                            </flux:link>
                        @endif
                    </div>
                    <flux:input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        viewable
                        :invalid="$errors->has('password')"
                    />
                    <flux:error name="password" />
                </flux:field>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <flux:checkbox id="remember" name="remember" />
                    <flux:label for="remember" class="text-sm font-normal text-zinc-600 dark:text-zinc-400">
                        Keep me signed in
                    </flux:label>
                </div>

                {{-- Submit --}}
                <flux:button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    data-test="login-button"
                >
                    Sign In
                </flux:button>
            </form>

            {{-- Divider --}}
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-200 dark:border-zinc-700"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-xs text-zinc-400 dark:bg-zinc-950">
                        Need help? Contact ICT Support
                    </span>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-zinc-400 dark:text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Graduate School Portal') }}.
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
