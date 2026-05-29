@extends('layouts.app')

@section('title', 'Book a table')

@php
    $inputClasses = 'w-full px-3 py-2 border-[1.5px] border-stone-200 rounded-lg text-sm bg-white outline-none focus:border-stone-900';
    $labelClasses = 'block text-sm font-medium text-stone-600 mb-1.5';
    $sectionTitleClasses = 'block text-xs font-semibold uppercase tracking-wider text-stone-500 mb-2';
    $errorClasses = 'text-red-800 text-xs mt-1.5';
    $selectedDate = old('date', $bookableSlotsByDate->keys()->first());
    $slotCapacity = (int) config('restaurant.slot_capacity');
@endphp

@section('content')
    <h1 class="text-2xl font-bold mb-6">Book a table</h1>

    @if (session('success'))
        <div class="bg-green-50 border-[1.5px] border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf

        <div class="mb-4">
            <label for="date" class="{{ $sectionTitleClasses }}">Date</label>
            <select id="date" name="date" class="{{ $inputClasses }}"
                    onchange="document.querySelectorAll('[data-slots]').forEach(g => g.classList.toggle('hidden', g.dataset.slots !== this.value))">
                @foreach ($bookableSlotsByDate as $date => $slots)
                    <option value="{{ $date }}" @selected($date === $selectedDate)>
                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                    </option>
                @endforeach
            </select>
            @error('date')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror
        </div>

        <p class="{{ $sectionTitleClasses }}">Available slots</p>
        @foreach ($bookableSlotsByDate as $date => $bookableSlots)
            <div data-slots="{{ $date }}"
                 @class(['flex flex-wrap gap-2', 'hidden' => $date !== $selectedDate])>
                @foreach ($bookableSlots as $bookableSlot)
                    @php
                        $occupation = $occupationsByDate[$date][$bookableSlot->slot->value] ?? null;
                        $occupationBgClass = match (true) {
                            $occupation === null => 'bg-white',
                            $occupation >= $slotCapacity => 'bg-red-100',
                            $occupation >= $slotCapacity / 2 => 'bg-amber-100',
                            default => 'bg-green-100',
                        };
                    @endphp
                    <label class="relative inline-flex flex-col items-center justify-center px-3.5 py-2 border-[1.5px] border-stone-200 rounded-lg {{ $occupationBgClass }} cursor-pointer text-sm font-medium min-w-[72px] select-none hover:border-stone-700 has-[:checked]:bg-stone-900 has-[:checked]:text-white has-[:checked]:border-stone-900">
                        <input type="radio" name="slot" value="{{ $bookableSlot->slot->value }}"
                               class="absolute opacity-0 pointer-events-none"
                               @checked((int) old('slot') === $bookableSlot->slot->value) required />
                        <span>{{ $bookableSlot->slot->getDisplayName() }}</span>
                        @isset($occupationsByDate)
                            <span class="text-[10px] font-normal opacity-70">
                                {{ $occupation ?? 0 }}/{{ $slotCapacity }}
                            </span>
                        @endisset
                    </label>
                @endforeach
            </div>
        @endforeach
        @error('slot')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror

        <div class="bg-white border-[1.5px] border-stone-200 rounded-xl p-6 mt-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Your details</p>
                <button type="button" onclick="autoFill()"
                        class="text-xs font-medium text-stone-600 hover:text-stone-900 underline cursor-pointer">
                    Auto fill
                </button>
            </div>

            <div class="mb-4">
                <label for="firstname" class="{{ $labelClasses }}">First name</label>
                <input type="text" id="firstname" name="firstname" maxlength="255"
                       class="{{ $inputClasses }}"
                       placeholder="John" value="{{ old('firstname') }}" required />
                @error('firstname')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="lastname" class="{{ $labelClasses }}">Last name</label>
                <input type="text" id="lastname" name="lastname" maxlength="255"
                       class="{{ $inputClasses }}"
                       placeholder="Doe" value="{{ old('lastname') }}" required />
                @error('lastname')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="email" class="{{ $labelClasses }}">Email</label>
                <input type="email" id="email" name="email" maxlength="255"
                       class="{{ $inputClasses }}"
                       placeholder="john.doe@example.com" value="{{ old('email') }}" required />
                @error('email')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="nb_guests" class="{{ $labelClasses }}">Number of guests</label>
                <input type="number" id="nb_guests" name="nb_guests" min="1" max="20"
                       class="{{ $inputClasses }}"
                       value="{{ old('nb_guests', 2) }}" required />
                @error('nb_guests')<p class="{{ $errorClasses }}">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-stone-900 text-white rounded-lg text-base font-semibold cursor-pointer mt-2 hover:bg-stone-800 disabled:bg-stone-500 disabled:cursor-not-allowed">
                Confirm reservation
            </button>
        </div>
    </form>

    <script>
        function autoFill() {
            const firstnames = ['Alice', 'Bob', 'Charlie', 'Diana', 'Ethan', 'Fiona', 'George', 'Hannah'];
            const lastnames  = ['Martin', 'Bernard', 'Dubois', 'Petit', 'Robert', 'Richard', 'Durand', 'Moreau'];
            const pick = arr => arr[Math.floor(Math.random() * arr.length)];
            const f = pick(firstnames);
            const l = pick(lastnames);
            document.getElementById('firstname').value = f;
            document.getElementById('lastname').value  = l;
            document.getElementById('email').value     = `${f.toLowerCase()}.${l.toLowerCase()}@example.com`;
        }
    </script>
@endsection
