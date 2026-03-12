<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #F7F5F2;
            --surface: #FFFFFF;
            --ink: #1A1A18;
            --ink-muted: #8A8880;
            --accent: #2D6A4F;
            --accent-light: #E8F4EF;
            --accent-dark: #1B4332;
            --border: #E8E4DF;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.08);
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            background-image: url("{{ asset('storage/background_image.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Overlay for readability */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            pointer-events: none;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 3.5rem 3rem;
            max-width: 440px;
            width: 100%;
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            animation: rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success icon */
        .icon-wrap {
            width: 72px;
            height: 72px;
            background: var(--accent-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        @keyframes pop {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .icon-wrap svg {
            width: 32px;
            height: 32px;
            stroke: var(--accent);
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Checkmark draw animation */
        .icon-wrap svg path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: draw 0.4s ease 0.5s forwards;
        }

        @keyframes draw {
            to { stroke-dashoffset: 0; }
        }

        .tag {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            background: var(--accent-light);
            border-radius: 100px;
            padding: 0.3rem 0.85rem;
            margin-bottom: 1.25rem;
            animation: fade 0.4s ease 0.3s both;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.85rem;
            line-height: 1.2;
            color: var(--ink);
            margin-bottom: 0.85rem;
            animation: fade 0.4s ease 0.4s both;
        }

        p {
            font-size: 0.92rem;
            color: var(--ink-muted);
            line-height: 1.65;
            font-weight: 300;
            margin-bottom: 2.5rem;
            animation: fade 0.4s ease 0.5s both;
        }

        @keyframes fade {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 2rem;
            animation: fade 0.4s ease 0.55s both;
        }

        .btn-dashboard {
            display: block;
            width: 100%;
            padding: 0.9rem 1.5rem;
            background: var(--accent);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
            animation: fade 0.4s ease 0.65s both;
            box-shadow: 0 4px 14px rgba(45, 106, 79, 0.28);
        }

        .btn-dashboard:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45, 106, 79, 0.36);
        }

        .btn-dashboard:active {
            transform: translateY(0);
        }

        /* Arrow icon in button */
        .btn-dashboard span {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-dashboard svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform 0.2s ease;
        }

        .btn-dashboard:hover svg {
            transform: translateX(3px);
        }
    </style>
</head>
<body>

    <div class="card">

        <div class="icon-wrap">
            <svg viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="tag">Success</div>

        <h1>Password Has BeenReset Successfully</h1>

        <div class="divider"></div>

        <a href="{{ route('welcome') }}" class="btn-dashboard">
            <span>
                Go to Login
                <svg viewBox="0 0 24 24">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </span>
        </a>

    </div>

</body>
</html>