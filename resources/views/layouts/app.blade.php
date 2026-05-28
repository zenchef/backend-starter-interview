<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Zenchef')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f4f0;
            color: #1a1a1a;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container { max-width: 560px; margin: 0 auto; }

        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; }

        .section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #888;
            margin-bottom: 0.5rem;
        }

        select, input {
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            font-size: 0.9rem;
            outline: none;
            background: #fff;
            width: 100%;
        }
        select:focus, input:focus { border-color: #1a1a1a; }

        .field { margin-bottom: 1rem; }
        label { display: block; font-size: 0.8rem; font-weight: 500; color: #555; margin-bottom: 0.35rem; }

        .slots-grid { display: flex; flex-wrap: wrap; gap: 0.5rem; }

        .slot-btn {
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            min-width: 72px;
        }
        .slot-btn:hover { border-color: #333; }
        .slot-btn.selected { border-color: #1a1a1a; background: #1a1a1a; color: #fff; }

        .form-section {
            background: #fff;
            border: 1.5px solid #e5e5e5;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

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
        }
        .submit-btn:hover { background: #333; }
        .submit-btn:disabled { background: #999; cursor: not-allowed; }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-top: 1rem;
        }
        .alert.success { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; }
        .alert.error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }

        [hidden] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
