<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LintasGo - Travel Antarkota')</title>
    <style>
        :root {
            --primary: #1e40af;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --success: #16a34a;
            --success-bg: #f0fdf4;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .auth-container {
            width: 100%;
            max-width: 440px;
        }
        .auth-container.wide {
            max-width: 500px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }
        .brand-header p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }
        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 28px 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-color);
        }
        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .alert-success {
            background-color: var(--success-bg);
            color: var(--success);
            border: 1px solid #bbf7d0;
        }
        .alert-danger {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid #fecaca;
        }
        .alert ul {
            margin-left: 20px;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-color);
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: #fff;
            color: var(--text-color);
            outline: none;
            transition: border-color 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.15);
        }
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            background-color: var(--primary);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }
        .btn-submit:hover {
            background-color: var(--primary-hover);
        }
        .auth-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .admin-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334155;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-container @yield('container-class')">
        <div class="brand-header">
            <h1>LintasGo</h1>
            <p>Sistem Pemesanan Tiket Travel Antarkota</p>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <div class="auth-footer">
            @yield('footer-links')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
