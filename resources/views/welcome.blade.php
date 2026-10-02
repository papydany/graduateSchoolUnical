<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head', ['title' => __('Welcome')])
</head>
<body class="min-h-screen bg-white text-zinc-700 antialiased">

@php
    $portalUrl = auth()->check() ? route('dashboard') : route('login');
    $portalLabel = auth()->check() ? 'Go to Dashboard' : 'Sign In';

    $studentPortalUrl = auth('student')->check() ? route('student.dashboard') : route('student.login');

    $programmeDetails = [
        'Post Graduate Diploma' => ['icon' => 'document-text', 'text' => 'Professional diploma programmes that build specialised knowledge after a first degree.'],
        'Master' => ['icon' => 'academic-cap', 'text' => 'Taught and research Masters degrees across the faculties of the university.'],
        'Ph.D' => ['icon' => 'light-bulb', 'text' => 'Doctoral research under supervision, leading to original contributions in your field.'],
    ];
@endphp

    {{-- ═══════════════════════════════════════════════
         TOP BAR
    ════════════════════════════════════════════════ --}}
    <div class="hidden bg-zinc-900 text-xs text-white/70 md:block">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2.5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <flux:icon.map-pin class="size-4 text-red-500" />
                    University of Calabar, Calabar, Cross River State
                </span>
               <!-- <span class="flex items-center gap-2">
                    <flux:icon.clock class="size-4 text-red-500" />
                    Mon – Fri: 8:00am – 4:00pm
                </span>-->
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('student.profile') }}" class="font-medium text-white hover:text-red-400 transition-colors">
                    Check Registration
                </a>
                <a href="{{ $studentPortalUrl }}" class="font-medium text-white hover:text-red-400 transition-colors">
                    {{ auth('student')->check() ? 'My Dashboard' : 'Student Portal' }}
                </a>
                <a href="{{ $portalUrl }}" class="font-medium text-white hover:text-red-400 transition-colors">
                    {{ auth()->check() ? 'My Dashboard' : 'Staff Login' }}
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         HEADER / NAV
    ════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-40 bg-white/95 shadow-sm backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-700">
                    <x-app-logo-icon class="size-6 fill-current text-white" />
                </div>
                <div class="min-w-0 leading-tight">
                    <p class="truncate text-base font-bold text-zinc-900">Graduate School</p>
                    <p class="truncate text-xs text-zinc-500">University of Calabar</p>
                </div>
            </a>

            <ul class="hidden items-center gap-8 text-sm font-semibold text-zinc-800 lg:flex">
                <li><a href="#top" class="hover:text-red-700 transition-colors">Home</a></li>
        <li><a href="#programmes" class="hover:text-red-700 transition-colors">Programmes</a></li>
                <li><a href="#faculties" class="hover:text-red-700 transition-colors">Faculties</a></li>
                <li><a href="#how-it-works" class="hover:text-red-700 transition-colors">How It Works</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <a href="{{ $portalUrl }}"
                   class="hidden rounded-full bg-red-700 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-800 transition-colors sm:inline-flex">
                    {{ $portalLabel }}
                </a>
                <button type="button" id="nav-toggle" aria-label="Open menu" aria-expanded="false"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 text-zinc-800 lg:hidden">
                    <flux:icon.bars-3 class="size-5" />
                </button>
            </div>
        </nav>

        {{-- Mobile menu --}}
        <div id="nav-menu" class="hidden border-t border-zinc-100 bg-white lg:hidden">
            <ul class="space-y-1 px-4 py-4 text-sm font-semibold text-zinc-800">
                <li><a href="#top" class="block rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-700">Home</a></li>
                <li><a href="#about" class="block rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-700">About</a></li>
                <li><a href="#programmes" class="block rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-700">Programmes</a></li>
                <li><a href="#faculties" class="block rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-700">Faculties</a></li>
                <li><a href="#how-it-works" class="block rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-700">How It Works</a></li>
                <li class="pt-2">
                    <a href="{{ $portalUrl }}" class="block rounded-full bg-red-700 px-4 py-2.5 text-center text-white">{{ $portalLabel }}</a>
                </li>
            </ul>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════════
         HERO
    ════════════════════════════════════════════════ --}}
    <section id="top" class="relative overflow-hidden bg-zinc-900">
        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-900/95 to-red-900/80"></div>
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;">
        </div>
        {{-- Decorative rings --}}
        <div class="absolute -right-32 -top-32 h-[28rem] w-[28rem] rounded-full border-[40px] border-red-700/20"></div>
        <div class="absolute -bottom-24 right-1/3 h-48 w-48 rounded-full border-[24px] border-white/5"></div>

        <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-4 py-24 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-32">
            <div class="space-y-7 lg:col-span-7">
                <p class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.2em] text-red-400">
                    <span class="h-px w-10 bg-red-500"></span>
                    Welcome to the Graduate School
                </p>
                <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
                    Advance Your Career Through
                    <span class="text-red-500">Postgraduate</span> Study
                </h1>
                <p class="max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">
                    Register your courses, follow your academic progress and check your results
                    online, whether you are on a PGD, Masters or Ph.D programme.
                </p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $portalUrl }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-red-700 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-900/40 hover:bg-red-800 transition-colors">
                        {{ auth()->check() ? 'Continue to Dashboard' : 'Access the Portal' }}
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                    <a href="#programmes"
                       class="inline-flex items-center justify-center rounded-full border border-white/30 px-8 py-3.5 text-sm font-semibold text-white hover:bg-white hover:text-zinc-900 transition-colors">
                        View All Programmes
                    </a>
                </div>
            </div>

            <div class="hidden lg:col-span-5 lg:block">
                <div class="relative mx-auto aspect-square max-w-sm">
                    <div class="absolute inset-0 rounded-full bg-red-700/20"></div>
                    <div class="absolute inset-6 flex flex-col items-center justify-center rounded-full border border-white/10 bg-white/5 text-center backdrop-blur">
                        <flux:icon.academic-cap class="size-20 text-red-400" />
                        <p class="mt-4 text-5xl font-extrabold text-white">{{ number_format($stats['programmes_of_study']) }}</p>
                        <p class="mt-1 text-sm uppercase tracking-widest text-white/60">Programmes of Study</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         FEATURE STRIP (overlaps hero)
    ════════════════════════════════════════════════ --}}
    <section class="relative z-20 -mt-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-px overflow-hidden rounded-2xl bg-zinc-200 shadow-xl sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => 'clipboard-document-check', 'title' => 'Course Registration', 'text' => 'Register your courses each session.'],
                ['icon' => 'document-chart-bar', 'title' => 'Check Results', 'text' => 'View your approved results online.'],
                ['icon' => 'book-open', 'title' => 'Programmes', 'text' => 'PGD, Masters and Ph.D programmes.'],
                ['icon' => 'shield-check', 'title' => 'Secure Records', 'text' => 'Your academic data, kept safe.'],
            ] as $feature)
                <div class="group flex items-start gap-4 bg-white p-6 transition-colors hover:bg-red-700">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-700 transition-colors group-hover:bg-white/15 group-hover:text-white">
                        <flux:icon :name="$feature['icon']" class="size-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-zinc-900 transition-colors group-hover:text-white">{{ $feature['title'] }}</h3>
                        <p class="mt-1 text-sm text-zinc-500 transition-colors group-hover:text-white/80">{{ $feature['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

   

    {{-- ═══════════════════════════════════════════════
         STATS COUNTER
    ════════════════════════════════════════════════ --}}
    <section class="bg-red-700">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-14 text-center sm:px-6 lg:grid-cols-4 lg:px-8">
            @foreach ([
                ['value' => $programmes->count(), 'label' => 'Programme Types'],
                ['value' => $stats['faculties'], 'label' => 'Faculties'],
                ['value' => $stats['departments'], 'label' => 'Departments'],
                ['value' => $stats['programmes_of_study'], 'label' => 'Programmes of Study'],
            ] as $stat)
                <div>
                    <p class="text-4xl font-extrabold text-white sm:text-5xl">{{ number_format($stat['value']) }}</p>
                    <p class="mt-2 text-sm font-medium uppercase tracking-widest text-white/70">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         PROGRAMMES
    ════════════════════════════════════════════════ --}}
    <section id="programmes" class="scroll-mt-24 bg-zinc-50 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.2em] text-red-700">
                    <span class="h-px w-8 bg-red-700"></span>
                    Programmes
                    <span class="h-px w-8 bg-red-700"></span>
                </p>
                <h2 class="mt-4 text-3xl font-extrabold text-zinc-900 sm:text-4xl">Explore our postgraduate programmes</h2>
            </div>

            <div class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ($programmes as $programme)
                    @php $details = $programmeDetails[$programme->name] ?? ['icon' => 'academic-cap', 'text' => 'Postgraduate programme offered by the Graduate School.']; @endphp
                    <div class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-100 transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative flex h-40 items-center justify-center bg-gradient-to-br from-zinc-800 to-zinc-950">
                            <div class="absolute inset-0 bg-red-700 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            <flux:icon :name="$details['icon']" class="relative size-16 text-white/90" />
                            <span class="absolute bottom-4 right-4 rounded-full bg-white px-3 py-1 text-xs font-bold text-red-700">
                                {{ $programme->programme_of_studies_count }} {{ Str::plural('programme', $programme->programme_of_studies_count) }}
                            </span>
                        </div>
                        <div class="p-7">
                            <h3 class="text-xl font-bold text-zinc-900">{{ $programme->name }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-zinc-500">{{ $details['text'] }}</p>
                            <a href="{{ $portalUrl }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-red-700 hover:gap-3 transition-all">
                                {{ auth()->check() ? 'Open Dashboard' : 'Sign In to Register' }}
                                <flux:icon.arrow-right class="size-4" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════
         FACULTIES
    ════════════════════════════════════════════════ --}}
    <section id="faculties" class="scroll-mt-24 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
                <div class="max-w-2xl">
                    <p class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.2em] text-red-700">
                        <span class="h-px w-10 bg-red-700"></span>
                        Faculties &amp; Departments
                    </p>
                    <h2 class="mt-4 text-3xl font-extrabold text-zinc-900 sm:text-4xl">Study across our faculties</h2>
                </div>
                <p class="text-sm text-zinc-500">
                    {{ $stats['faculties'] }} faculties · {{ number_format($stats['departments']) }} departments
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredFaculties as $faculty)
                    <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 p-7 transition hover:border-red-700 hover:shadow-lg">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-red-50 transition-transform group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-700 text-white">
                                <flux:icon.building-office-2 class="size-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-zinc-900">
                                Faculty of {{ str_replace([' And ', ' Of '], [' and ', ' of '], Str::title(strtolower($faculty->faculty_name))) }}
                            </h3>
                            <p class="mt-2 text-sm text-zinc-500">
                                {{ $faculty->departments_count }} {{ Str::plural('department', $faculty->departments_count) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         HOW IT WORKS
    ════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="scroll-mt-24 bg-zinc-50 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.2em] text-red-700">
                    <span class="h-px w-8 bg-red-700"></span>
                    How It Works
                    <span class="h-px w-8 bg-red-700"></span>
                </p>
                <h2 class="mt-4 text-3xl font-extrabold text-zinc-900 sm:text-4xl">Get started in three steps</h2>
            </div>

            <ol class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ([
                    ['icon' => 'key', 'title' => 'Sign in', 'text' => 'Use the email address and password issued to you by the Graduate School.'],
                    ['icon' => 'clipboard-document-list', 'title' => 'Register your courses', 'text' => 'Pick the courses for the current session and submit your registration.'],
                    ['icon' => 'chart-bar', 'title' => 'Track your results', 'text' => 'Return to the portal to view your results once they are approved.'],
                ] as $step)
                    <li class="relative rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-zinc-100">
                        <span class="absolute right-6 top-4 text-5xl font-extrabold text-zinc-100">0{{ $loop->iteration }}</span>
                        <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-700 text-white ring-8 ring-red-50">
                            <flux:icon :name="$step['icon']" class="size-7" />
                        </div>
                        <h3 class="relative mt-6 text-lg font-bold text-zinc-900">{{ $step['title'] }}</h3>
                        <p class="relative mt-2 text-sm leading-relaxed text-zinc-500">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════ --}}
    <footer class="bg-zinc-950 text-sm text-white/60">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-700">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </div>
                    <div class="leading-tight">
                        <p class="text-base font-bold text-white">Graduate School</p>
                        <p class="text-xs">University of Calabar</p>
                    </div>
                </div>
                <p class="mt-5 max-w-sm leading-relaxed">
                    The online portal for postgraduate course registration and results at the
                    University of Calabar Graduate School.
                </p>
            </div>

            <div>
                <h4 class="text-base font-bold text-white">Quick Links</h4>
                <span class="mt-2 block h-0.5 w-10 bg-red-700"></span>
                <ul class="mt-5 space-y-3">
                    <li><a href="#about" class="hover:text-red-400 transition-colors">About</a></li>
                    <li><a href="#programmes" class="hover:text-red-400 transition-colors">Programmes</a></li>
                    <li><a href="#faculties" class="hover:text-red-400 transition-colors">Faculties</a></li>
                    <li><a href="#how-it-works" class="hover:text-red-400 transition-colors">How It Works</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-base font-bold text-white">Portal</h4>
                <span class="mt-2 block h-0.5 w-10 bg-red-700"></span>
                <ul class="mt-5 space-y-3">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-red-400 transition-colors">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-red-400 transition-colors">Sign In</a></li>
                        @if (Route::has('password.request'))
                            <li><a href="{{ route('password.request') }}" class="hover:text-red-400 transition-colors">Forgot Password</a></li>
                        @endif
                    @endauth
                    <li><a href="{{ $studentPortalUrl }}" class="hover:text-red-400 transition-colors">Student Portal</a></li>
                    <li><a href="{{ route('student.profile') }}" class="hover:text-red-400 transition-colors">Check Registration</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <p class="mx-auto max-w-7xl px-4 py-6 text-center text-xs sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name', '') }}. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        // Mobile menu
        (function () {
            const toggle = document.getElementById('nav-toggle');
            const menu = document.getElementById('nav-menu');
            toggle.addEventListener('click', () => {
                const open = menu.classList.toggle('hidden') === false;
                toggle.setAttribute('aria-expanded', open);
            });
            menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => menu.classList.add('hidden')));
        })();

        // About tabs
        document.querySelectorAll('[data-tabs]').forEach((tabs) => {
            tabs.querySelectorAll('[data-tab]').forEach((button) => {
                button.addEventListener('click', () => {
                    tabs.querySelectorAll('[data-tab]').forEach((b) => b.setAttribute('aria-selected', b === button));
                    tabs.querySelectorAll('[data-panel]').forEach((panel) => {
                        panel.hidden = panel.dataset.panel !== button.dataset.tab;
                    });
                });
            });
        });
    </script>

    @fluxScripts
</body>
</html>
