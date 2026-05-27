<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurant Booking</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f4f0;
            color: #1a1a1a;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
        }

        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .subtitle { color: #666; font-size: 0.9rem; margin-bottom: 2rem; }

        .section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #888;
            margin-bottom: 0.75rem;
        }

        /* Slots grid */
        .slots-section { margin-bottom: 2rem; }

        .slots-group { margin-bottom: 1.25rem; }

        .slots-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .slot-btn {
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            min-width: 72px;
        }

        .slot-btn:hover:not(:disabled) {
            border-color: #333;
            background: #fafafa;
        }

        .slot-btn.selected {
            border-color: #1a1a1a;
            background: #1a1a1a;
            color: #fff;
        }

        .slot-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        .slot-covers {
            font-size: 0.7rem;
            font-weight: 400;
            opacity: 0.7;
        }

        .slot-btn.selected .slot-covers { opacity: 0.8; }

        /* Booking form */
        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1.5px solid #e5e5e5;
            display: none;
        }

        .form-section.visible { display: block; }

        .form-header {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .form-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.875rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1;
            gap: 0.35rem;
        }

        label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #555;
        }

        input {
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.15s;
            width: 100%;
        }

        input:focus { border-color: #1a1a1a; }

        input.error { border-color: #e53e3e; }

        .field-error {
            font-size: 0.75rem;
            color: #e53e3e;
            display: none;
        }

        .field-error.visible { display: block; }

        .submit-btn {
            width: 100%;
            padding: 0.7rem;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.15s;
        }

        .submit-btn:hover { background: #333; }
        .submit-btn:disabled { background: #999; cursor: not-allowed; }

        /* Alerts */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-top: 1rem;
            display: none;
        }

        .alert.visible { display: block; }
        .alert.success { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; }
        .alert.error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }

        /* Loading */
        .loading { color: #888; font-size: 0.9rem; padding: 1rem 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book a table</h1>
        <p class="subtitle" id="date-label">Loading availability…</p>

        <div class="slots-section" id="slots-section">
            <p class="loading" id="slots-loading">Loading slots…</p>
        </div>

        <div class="form-section" id="booking-form">
            <p class="form-header" id="form-header">Reserve your slot</p>

            <div class="form-row">
                <div class="form-group">
                    <label for="customer_name">Name</label>
                    <input type="text" id="customer_name" placeholder="Jean Dupont" autocomplete="name" />
                    <span class="field-error" id="err-customer_name"></span>
                </div>
                <div class="form-group">
                    <label for="covers">Covers</label>
                    <input type="number" id="covers" min="1" max="10" value="2" />
                    <span class="field-error" id="err-covers"></span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0.875rem">
                <label for="customer_email">Email</label>
                <input type="email" id="customer_email" placeholder="jean@example.com" autocomplete="email" />
                <span class="field-error" id="err-customer_email"></span>
            </div>

            <button class="submit-btn" id="submit-btn">Confirm reservation</button>

            <div class="alert success" id="alert-success"></div>
            <div class="alert error"   id="alert-error"></div>
        </div>
    </div>

    <script>
        let selectedSlotId = null;

        // ── Fetch slots ───────────────────────────────────────────────────────
        async function loadSlots() {
            const res  = await fetch('/api/slots');
            const data = await res.json();

            document.getElementById('date-label').textContent =
                `Availability for ${new Date(data.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' })}`;

            const section  = document.getElementById('slots-section');
            section.innerHTML = '';

            const lunch  = data.slots.filter(s => s.period === 'lunch');
            const dinner = data.slots.filter(s => s.period === 'dinner');

            if (lunch.length)  section.appendChild(renderGroup('Lunch',  lunch));
            if (dinner.length) section.appendChild(renderGroup('Dinner', dinner));
        }

        function renderGroup(label, slots) {
            const div = document.createElement('div');
            div.className = 'slots-group';
            div.innerHTML = `<p class="section-title">${label}</p><div class="slots-grid" id="grid-${label.toLowerCase()}"></div>`;
            const grid = div.querySelector('.slots-grid');
            slots.forEach(slot => grid.appendChild(renderSlotBtn(slot)));
            return div;
        }

        function renderSlotBtn(slot) {
            const btn = document.createElement('button');
            btn.className  = 'slot-btn';
            btn.disabled   = !slot.available;
            btn.dataset.id = slot.id;
            btn.innerHTML  = `<span>${slot.time}</span><span class="slot-covers">${slot.available ? slot.available_covers + ' left' : 'full'}</span>`;
            btn.addEventListener('click', () => selectSlot(slot, btn));
            return btn;
        }

        function selectSlot(slot, btn) {
            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            selectedSlotId = slot.id;

            const form = document.getElementById('booking-form');
            form.classList.add('visible');
            document.getElementById('form-header').textContent = `Reserve ${slot.time} — up to ${slot.available_covers} cover${slot.available_covers > 1 ? 's' : ''}`;
            document.getElementById('covers').max = slot.available_covers;

            clearAlerts();
        }

        // ── Submit reservation ────────────────────────────────────────────────
        document.getElementById('submit-btn').addEventListener('click', async () => {
            clearAlerts();
            clearFieldErrors();

            const body = {
                time_slot_id:   selectedSlotId,
                customer_name:  document.getElementById('customer_name').value.trim(),
                customer_email: document.getElementById('customer_email').value.trim(),
                covers:         parseInt(document.getElementById('covers').value, 10),
            };

            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.textContent = 'Confirming…';

            try {
                const res  = await fetch('/api/reservations', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body:    JSON.stringify(body),
                });

                const data = await res.json();

                if (res.ok) {
                    showSuccess(`Reservation confirmed! See you at ${data.reservation?.time_slot?.time ?? 'your slot'}.`);
                    document.getElementById('booking-form').classList.remove('visible');
                    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
                    selectedSlotId = null;
                    await loadSlots(); // refresh availability
                } else if (res.status === 422 && data.errors) {
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const el = document.getElementById(`err-${field}`);
                        const input = document.getElementById(field);
                        if (el) { el.textContent = messages[0]; el.classList.add('visible'); }
                        if (input) input.classList.add('error');
                    });
                } else {
                    showError(data.message ?? 'Something went wrong. Please try again.');
                }
            } catch (e) {
                showError('Could not reach the server. Is it running?');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Confirm reservation';
            }
        });

        function showSuccess(msg) {
            const el = document.getElementById('alert-success');
            el.textContent = msg;
            el.classList.add('visible');
            document.getElementById('booking-form').classList.add('visible');
        }

        function showError(msg) {
            const el = document.getElementById('alert-error');
            el.textContent = msg;
            el.classList.add('visible');
        }

        function clearAlerts() {
            ['alert-success', 'alert-error'].forEach(id => {
                const el = document.getElementById(id);
                el.textContent = '';
                el.classList.remove('visible');
            });
        }

        function clearFieldErrors() {
            document.querySelectorAll('.field-error').forEach(el => {
                el.textContent = '';
                el.classList.remove('visible');
            });
            document.querySelectorAll('input.error').forEach(el => el.classList.remove('error'));
        }

        loadSlots();
    </script>
</body>
</html>
