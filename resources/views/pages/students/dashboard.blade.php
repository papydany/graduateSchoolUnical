<x-layouts::auth.simple :title="__('Student Dashboard')">
    <div class="space-y-4 text-center">
        <flux:heading size="xl">
            Welcome, {{ $student->firstname }} {{ $student->surname }}
        </flux:heading>
        <flux:text>
            {{ $student->matriculation_number }}
            @if ($studentType)
                &middot; {{ $studentType === 'new' ? 'New Student' : 'Returning Student' }}
            @endif
        </flux:text>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <flux:button type="submit" variant="ghost" class="w-full">Log out</flux:button>
        </form>
    </div>
</x-layouts::auth.simple>
