<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Real Estate CRM') }}</title>
    <style>
        :root { color-scheme: light dark; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(1200px 600px at 50% -10%, #1f2937, #0b1120);
            color: #e5e7eb;
        }
        .wrap { width: 100%; max-width: 880px; }
        .header { text-align: center; margin-bottom: 40px; }
        .badge {
            display: inline-block; font-size: 12px; letter-spacing: .12em; text-transform: uppercase;
            color: #34d399; border: 1px solid rgba(52,211,153,.35); border-radius: 999px; padding: 6px 14px; margin-bottom: 18px;
        }
        h1 { font-size: 34px; font-weight: 700; color: #f9fafb; margin-bottom: 10px; }
        .sub { color: #9ca3af; font-size: 16px; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }
        .card {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px; padding: 26px; backdrop-filter: blur(8px);
        }
        .card h2 { font-size: 20px; color: #f9fafb; margin-bottom: 6px; }
        .card p { color: #9ca3af; font-size: 14px; margin-bottom: 18px; }
        .btn {
            display: block; text-align: center; text-decoration: none; font-weight: 600; font-size: 15px;
            padding: 12px 16px; border-radius: 10px; transition: transform .05s ease, opacity .2s ease;
        }
        .btn:active { transform: translateY(1px); }
        .btn-primary { background: linear-gradient(180deg, #34d399, #10b981); color: #052e1b; }
        .btn-amber { background: linear-gradient(180deg, #fbbf24, #f59e0b); color: #3a2606; }
        .quick { margin-top: 16px; border-top: 1px dashed rgba(255,255,255,.12); padding-top: 16px; }
        .quick-label { font-size: 12px; text-transform: uppercase; letter-spacing: .1em; color: #6b7280; margin-bottom: 10px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip {
            text-decoration: none; font-size: 13px; padding: 7px 12px; border-radius: 8px;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: #d1d5db;
        }
        .chip:hover { background: rgba(255,255,255,.12); }
        .foot { text-align: center; color: #6b7280; font-size: 13px; margin-top: 28px; }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="header">
            <h1>{{ config('app.name', 'Real Estate CRM') }}</h1>
            <p class="sub">Choose a panel to continue.</p>
        </div>

        @php
            $adminUrl = url(config('settings.panels.admin.path'));
            $agentUrl = url(config('settings.panels.agent.path'));
        @endphp

        <div class="grid">
            <div class="card">
                <h2>Admin Panel</h2>
                <p>Manage agents, monitor task progress, and impersonate agents.</p>
                <a class="btn btn-amber" href="{{ $adminUrl }}">Go to Admin Panel</a>
            </div>

            <div class="card">
                <h2>Agent Workspace</h2>
                <p>Manage contacts and tasks, and track them on a personal dashboard.</p>
                <a class="btn btn-primary" href="{{ $agentUrl }}">Go to Agent Panel</a>
            </div>
        </div>
    </div>
</body>

</html>
