<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => __('Welcome')])
</head>
<body class="min-h-screen bg-white text-zinc-800 antialiased">

    {{-- ═══════════════════════════════════════════════
         NAVBAR
    ════════════════════════════════════════════════ --}}
    <header class="absolute inset-x-0 top-0 z-20">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10 ring-1 ring-white/20">
                    <x-app-logo-icon class="size-6 fill-current text-white" />
                </div>
                <span class="hidden truncate text-sm font-semibold uppercase tracking-wide text-white sm:block">
                    {{ config('app.name', 'Graduate School Portal') }}
                </span>
            </a>

            @if (Route::has('login'))
                <div class="flex shrink-0 items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-blue-950 shadow hover:bg-blue-50 transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-blue-950 shadow hover:bg-blue-50 transition-colors">
                            Sign In
                        </a>
                    @endauth
                </div>
            @endif
        </nav>
    </header>

    {{-- ═══════════════════════════════════════════════
         HERO
    ════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-blue-950">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 opacity-90"></div>
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;">
        </div>

        <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-4 pb-20 pt-32 sm:px-6 lg:grid-cols-2 lg:px-8 lg:pb-28 lg:pt-40">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-medium uppercase tracking-widest text-white/70">
                    <flux:icon.academic-cap class="size-4" />
                    Postgraduate Studies
                </div>
                <h1 class="text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Your Postgraduate Journey,<br>
                    <span class="text-white/60">All in One Place.</span>
                </h1>
                <p class="max-w-xl text-base leading-relaxed text-white/60 sm:text-lg">
                    Register your courses, follow your academic progress and check your
                    results through the Graduate School portal, built for Masters, PGD
                    and PhD students.
                </p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-blue-950 shadow hover:bg-blue-50 transition-colors">
                            Continue to Dashboard
                            <flux:icon.arrow-right class="size-4" />
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-blue-950 shadow hover:bg-blue-50 transition-colors">
                            Sign In to the Portal
                            <flux:icon.arrow-right class="size-4" />
                        </a>
                    @endauth
                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition-colors">
                        How It Works
                    </a>
                </div>
            </div>

            {{-- Hero card --}}
            <div class="hidden lg:block">
                <div class="ml-auto max-w-md rounded-2xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-widest text-white/50">Student Services</p>
                    <ul class="mt-5 space-y-4">
                        @foreach ([
                            ['icon' => 'clipboard-document-list', 'title' => 'Course Registration', 'text' => 'Register courses for your programme each session.'],
                            ['icon' => 'chart-bar', 'title' => 'Results', 'text' => 'View approved results semester by semester.'],
                            ['icon' => 'user-circle', 'title' => 'Student Profile', 'text' => 'Keep your details and programme information current.'],
                        ] as $item)
                            <li class="flex items-start gap-4 rounded-xl bg-white/5 p-4 ring-1 ring-white/10">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10">
                                    <flux:icon :name="$item['icon']" class="size-5 text-white" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $item['title'] }}</p>
                                    <p class="mt-0.5 text-sm text-white/50">{{ $item['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         FEATURES
    ════════════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-900">What you can do</p>
                <h2 class="mt-2 text-3xl font-bold text-zinc-900">Everything you need for your studies</h2>
                <p class="mt-4 text-zinc-500">
                    The portal brings the key parts of postgraduate administration together,
                    so you spend less time in queues and more time on your research.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'clipboard-document-check', 'title' => 'Course Registration', 'text' => 'Choose and register the courses for your programme and specialization.'],
                    ['icon' => 'document-chart-bar', 'title' => 'Check Results', 'text' => 'See your approved results as soon as your department publishes them.'],
                    ['icon' => 'book-open', 'title' => 'Programmes of Study', 'text' => 'Stay on track with the requirements of your Masters, PGD or PhD programme.'],
                    ['icon' => 'shield-check', 'title' => 'Secure Access', 'text' => 'Your records are protected, and only you and authorised staff can see them.'],
                ] as $feature)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-950 text-white">
                            <flux:icon :name="$feature['icon']" class="size-5" />
                        </div>
                        <h3 class="mt-5 text-base font-semibold text-zinc-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ $feature['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         HOW IT WORKS
    ════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="scroll-mt-8 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-900">How it works</p>
                <h2 class="mt-2 text-3xl font-bold text-zinc-900">Get started in three steps</h2>
            </div>

            <ol class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Sign in', 'text' => 'Use the email address and password issued to you by the Graduate School.'],
                    ['title' => 'Register your courses', 'text' => 'Pick the courses for the current session and submit your registration.'],
                    ['title' => 'Track your results', 'text' => 'Return to the portal to view your results once they are approved.'],
                ] as $step)
                    <li class="relative rounded-2xl border border-zinc-200 p-6">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-950 text-sm font-bold text-white">
                            {{ $loop->iteration }}
                        </span>
                        <h3 class="mt-5 text-base font-semibold text-zinc-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         CALL TO ACTION
    ════════════════════════════════════════════════ --}}
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl bg-blue-950 px-6 py-14 text-center sm:px-12">
            <div class="absolute inset-0 opacity-5"
                 style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;">
            </div>
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-white sm:text-3xl">Ready to continue your studies?</h2>
                <p class="mx-auto mt-3 max-w-xl text-white/60">
                    Sign in to register your courses and check your results.
                    Contact the Graduate School if you have not received your login details.
                </p>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                   class="mt-8 inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-blue-950 shadow hover:bg-blue-50 transition-colors">
                    {{ auth()->check() ? 'Go to Dashboard' : 'Sign In Now' }}
                    <flux:icon.arrow-right class="size-4" />
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-200">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 py-8 text-center sm:flex-row sm:px-6 sm:text-left lg:px-8">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-blue-950">
                    <x-app-logo-icon class="size-4 fill-current text-white" />
                </div>
                <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">
                    {{ config('app.name', 'Graduate School Portal') }}
                </span>
            </div>
            <p class="text-xs text-zinc-400">
                &copy; {{ date('Y') }} {{ config('app.name', '') }}. All rights reserved.
            </p>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
