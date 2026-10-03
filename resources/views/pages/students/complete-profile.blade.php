<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head', ['title' => 'Complete Profile'])
</head>
<body class="min-h-screen bg-zinc-50 antialiased dark:bg-zinc-950">

<div class="mx-auto w-full max-w-4xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2" wire:navigate>
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-900 dark:bg-blue-900/40">
            <x-app-logo-icon class="size-5 fill-current text-white" />
        </div>
        <span class="text-base font-semibold text-zinc-800 dark:text-white">
            {{ config('app.name', 'Graduate School Portal') }}
        </span>
    </a>

    <div class="mb-6 space-y-1">
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">Complete Your Profile</flux:heading>
        <flux:text class="text-zinc-500 dark:text-zinc-400">
            Confirm your admission details below and fill in the remaining bio data.
        </flux:text>
    </div>

    {{-- Validation errors summary --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800/40 dark:bg-red-900/20">
            <div class="flex items-start gap-2">
                <flux:icon.exclamation-triangle class="mt-0.5 size-4 shrink-0 text-red-500" />
                <div>
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">Please fix the following errors:</p>
                    <ul class="mt-1 list-disc list-inside space-y-0.5 text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Default profile --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-800">
            <flux:heading size="lg">Admission Details</flux:heading>
        </div>
        <dl class="grid gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                'Surname' => $student->surname,
                'First Name' => $student->firstname,
                'Other Name' => $student->othername,
                'Registration Number' => $student->registration_number,
                'Matriculation Number' => $student->matriculation_number,
                'Entry Session' => $session,
                'Faculty' => $faculty,
                'Department' => $department,
                'Programme' => $programme,
            ] as $label => $value)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-medium text-zinc-900 dark:text-white">{{ filled($value) ? $value : '—' }}</dd>
                </div>
            @endforeach
        </dl>
    </div>

    {{-- Bio data form --}}
    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data"
          class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        @csrf

        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-800">
            <flux:heading size="lg">Bio Data</flux:heading>
        </div>

        <div class="grid gap-5 px-6 py-5 sm:grid-cols-2">

            {{-- Programme of study --}}
            <flux:field>
                <flux:label for="programme_of_study_id">Programme of Study</flux:label>
                <flux:select id="programme_of_study_id" name="programme_of_study_id" required :invalid="$errors->has('programme_of_study_id')">
                    <option value="">— Select programme of study —</option>
                    @foreach ($programmesOfStudy as $pos)
                        <option value="{{ $pos->id }}" @selected(old('programme_of_study_id') == $pos->id)>{{ $pos->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="programme_of_study_id" />
            </flux:field>

            {{-- Specialization --}}
            <flux:field>
                <flux:label for="specialization_id">Specialization</flux:label>
                <flux:select id="specialization_id" name="specialization_id" :invalid="$errors->has('specialization_id')">
                    <option value="">— {{ $specializations->isEmpty() ? 'None available' : 'Select specialization' }} —</option>
                    @foreach ($specializations as $spec)
                        <option value="{{ $spec->id }}" @selected(old('specialization_id') == $spec->id)>{{ $spec->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="specialization_id" />
            </flux:field>

            {{-- Email --}}
            <flux:field>
                <flux:label for="email">Email Address</flux:label>
                <flux:input id="email" name="email" type="email" :value="old('email', $student->email)"
                            required autocomplete="email" :invalid="$errors->has('email')" />
                <flux:error name="email" />
            </flux:field>

            {{-- Phone --}}
            <flux:field>
                <flux:label for="phone">Phone Number</flux:label>
                <flux:input id="phone" name="phone" type="tel" :value="old('phone', $student->phone)"
                            required autocomplete="tel" :invalid="$errors->has('phone')" />
                <flux:error name="phone" />
            </flux:field>

            {{-- Gender --}}
            <flux:field>
                <flux:label for="gender">Gender</flux:label>
                <flux:select id="gender" name="gender" required :invalid="$errors->has('gender')">
                    <option value="">— Select gender —</option>
                    @foreach ($genders as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $student->gender) === $gender)>{{ $gender }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="gender" />
            </flux:field>

            {{-- Marital status --}}
            <flux:field>
                <flux:label for="matrital_status">Marital Status</flux:label>
                <flux:select id="matrital_status" name="matrital_status" required :invalid="$errors->has('matrital_status')">
                    <option value="">— Select marital status —</option>
                    @foreach ($maritalStatuses as $maritalStatus)
                        <option value="{{ $maritalStatus }}" @selected(old('matrital_status', $student->matrital_status) === $maritalStatus)>{{ $maritalStatus }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="matrital_status" />
            </flux:field>

            {{-- State of origin --}}
            <flux:field>
                <flux:label for="state">State of Origin</flux:label>
                <flux:select id="state" name="state" required :invalid="$errors->has('state')">
                    <option value="">— Select state —</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" @selected(old('state', $student->state) == $state->id)>{{ trim($state->state_name) }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="state" />
            </flux:field>

            {{-- Local government --}}
            <flux:field>
                <flux:label for="lga">Local Government Area</flux:label>
                <flux:select id="lga" name="lga" required :invalid="$errors->has('lga')" data-selected="{{ old('lga', $student->lga) }}">
                    <option value="">— Select state first —</option>
                </flux:select>
                <flux:error name="lga" />
            </flux:field>

            {{-- Passport --}}
            <flux:field>
                <flux:label for="passport">Passport Photograph</flux:label>
                <input id="passport" name="passport" type="file" accept="image/jpeg,image/png" required
                       class="block w-full rounded-lg border border-zinc-200 text-sm text-zinc-700 file:mr-3 file:border-0 file:bg-blue-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-blue-950 dark:border-zinc-700 dark:text-zinc-300" />
                <flux:description>JPG or PNG, not larger than 200KB.</flux:description>
                <flux:error name="passport" />
            </flux:field>

            <div class="flex items-center">
                <img id="passport-preview" alt="Passport preview"
                     class="hidden h-28 w-24 rounded-lg border border-zinc-200 object-cover dark:border-zinc-700" />
            </div>

            {{-- Password --}}
            <flux:field>
                <flux:label for="password">Password</flux:label>
                <flux:input id="password" name="password" type="password" required viewable
                            autocomplete="new-password" :invalid="$errors->has('password')" />
                <flux:description>At least 6 characters. You will use this to log in.</flux:description>
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label for="password_confirmation">Confirm Password</flux:label>
                <flux:input id="password_confirmation" name="password_confirmation" type="password" required viewable
                            autocomplete="new-password" />
            </flux:field>
        </div>

        <div class="flex justify-end border-t border-zinc-200 px-6 py-4 dark:border-zinc-800">
            <flux:button type="submit" variant="primary" class="bg-blue-900! hover:bg-blue-950! text-white!"
                         data-test="complete-profile-button">
                Submit
            </flux:button>
        </div>
    </form>

    <p class="mt-8 text-center text-xs text-zinc-400 dark:text-zinc-500">
        &copy; {{ date('Y') }} {{ config('app.name', '') }}. All rights reserved.
    </p>
</div>

<script>
    (function () {
        const stateSelect = document.getElementById('state');
        const lgaSelect = document.getElementById('lga');
        const urlTemplate = @js(route('student.profile.lgas', ['stateId' => '__ID__']));

        function setOptions(placeholder, lgas = []) {
            const selected = lgaSelect.dataset.selected;
            lgaSelect.innerHTML = '';
            lgaSelect.add(new Option(placeholder, ''));
            lgas.forEach(lga => {
                lgaSelect.add(new Option(lga.lga_name.trim(), lga.id, false, String(lga.id) === selected));
            });
        }

        function loadLgas() {
            const stateId = stateSelect.value;
            if (!stateId) return setOptions('— Select state first —');

            setOptions('Loading…');
            fetch(urlTemplate.replace('__ID__', stateId), { headers: { 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => setOptions('— Select local government —', data))
                .catch(() => setOptions('Could not load local governments'));
        }

        stateSelect.addEventListener('change', () => { lgaSelect.dataset.selected = ''; loadLgas(); });
        loadLgas();

        document.getElementById('passport').addEventListener('change', function () {
            const preview = document.getElementById('passport-preview');
            const file = this.files[0];
            if (!file) return preview.classList.add('hidden');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    })();
</script>

@fluxScripts
</body>
</html>
