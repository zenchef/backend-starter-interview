@extends('layouts.app')

@section('title', 'Book a table')

@section('content')
    <h1>Book a table</h1>

    <div class="field">
        <label for="date-select" class="section-title">Date</label>
        <select id="date-select">
            @foreach ($bookableSlotsByDate as $date => $slots)
                <option value="{{ $date }}">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <p class="section-title">Available slots</p>
    @foreach ($bookableSlotsByDate as $date => $bookableSlots)
        <div class="slots-grid" data-date="{{ $date }}" @unless ($loop->first) hidden @endunless>
            @foreach ($bookableSlots as $bookableSlot)
                <button type="button"
                        class="slot-btn"
                        data-slot="{{ $bookableSlot->slot->value }}"
                        data-name="{{ $bookableSlot->slot->getDisplayName() }}">
                    {{ $bookableSlot->slot->getDisplayName() }}
                </button>
            @endforeach
        </div>
    @endforeach

    <form id="booking-form" class="form-section" hidden>
        <p class="section-title" id="form-header">Your details</p>
        <input type="hidden" name="slot" id="slot" />

        <div class="field">
            <label for="firstname">First name</label>
            <input type="text" id="firstname" name="firstname" required />
        </div>
        <div class="field">
            <label for="lastname">Last name</label>
            <input type="text" id="lastname" name="lastname" required />
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
        </div>
        <div class="field">
            <label for="nb_guests">Number of guests</label>
            <input type="number" id="nb_guests" name="nb_guests" min="1" value="2" required />
        </div>

        <button type="submit" class="submit-btn">Confirm reservation</button>
        <div class="alert success" id="alert-success" hidden></div>
        <div class="alert error" id="alert-error" hidden></div>
    </form>
@endsection

@push('scripts')
<script>
    const dateSelect = document.getElementById('date-select');
    const form       = document.getElementById('booking-form');
    const header     = document.getElementById('form-header');
    const hiddenSlot = document.getElementById('slot');
    const okAlert    = document.getElementById('alert-success');
    const errAlert   = document.getElementById('alert-error');

    dateSelect.addEventListener('change', () => {
        document.querySelectorAll('.slots-grid').forEach(g => g.hidden = g.dataset.date !== dateSelect.value);
        form.hidden = true;
        clearAlerts();
    });

    document.querySelectorAll('.slot-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            hiddenSlot.value = btn.dataset.slot;
            header.textContent = `Booking ${dateSelect.value} at ${btn.dataset.name}`;
            form.hidden = false;
            clearAlerts();
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearAlerts();

        const submit = form.querySelector('.submit-btn');
        submit.disabled = true;

        try {
            const res = await fetch('/api/bookings', {
                method:  'POST',
                headers: {
                    'Content-Type':    'application/json',
                    'Accept':          'application/json',
                    'X-CSRF-TOKEN':    document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    date:      dateSelect.value,
                    slot:      parseInt(hiddenSlot.value, 10),
                    firstname: document.getElementById('firstname').value.trim(),
                    lastname:  document.getElementById('lastname').value.trim(),
                    email:     document.getElementById('email').value.trim(),
                    nb_guests: parseInt(document.getElementById('nb_guests').value, 10),
                }),
            });
            const data = await res.json();

            if (res.ok) {
                okAlert.textContent = 'Reservation confirmed!';
                okAlert.hidden = false;
                form.reset();
            } else {
                errAlert.textContent = data.message ?? 'Something went wrong.';
                errAlert.hidden = false;
            }
        } catch {
            errAlert.textContent = 'Could not reach the server.';
            errAlert.hidden = false;
        } finally {
            submit.disabled = false;
        }
    });

    function clearAlerts() {
        okAlert.hidden = true;
        errAlert.hidden = true;
    }
</script>
@endpush
