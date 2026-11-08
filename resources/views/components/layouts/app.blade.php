<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'QuestionMKR — প্রশ্ন ব্যাংক ও পরীক্ষা সিস্টেম' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Hind Siliguri', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="focus:outline-none">
                <x-logo />
            </a>

            <div class="flex items-center gap-4 text-sm font-semibold">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-700 hover:text-indigo-600">অ্যাডমিন পোর্টাল</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-slate-100 px-3.5 py-2 text-slate-700 hover:bg-slate-200">
                                লগআউট
                            </button>
                        </form>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-slate-700 hover:text-indigo-600">ড্যাশবোর্ড</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-slate-100 px-3.5 py-2 text-slate-700 hover:bg-slate-200">
                                লগআউট
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-indigo-600">লগইন</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">
                        নিবন্ধন করুন
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</body>
</html>
