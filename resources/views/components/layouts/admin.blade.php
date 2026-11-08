@props([
    'title' => 'অ্যাডমিন পোর্টাল',
    'heading' => null,
])

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — QuestionMKR</title>

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
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    @php
        $admin = auth()->user();
        $avatarUrl = 'https://ui-avatars.com/api/?name='.urlencode($admin?->organization_name ?? 'Admin').'&background=4f46e5&color=ffffff&bold=true';
        $isQuestionsActive = request()->routeIs('admin.questions.*');
    @endphp

    <div class="flex min-h-screen">
        <!-- Mobile Sidebar Backdrop -->
        <div
            id="sidebar-backdrop"
            class="fixed inset-0 z-30 hidden bg-slate-950/60 backdrop-blur-xs lg:hidden"
            onclick="toggleAdminSidebar()"
        ></div>

        <!-- Sidebar -->
        <aside
            id="admin-sidebar"
            class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-slate-900 text-slate-100 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
        >
            <!-- 1st in Sidebar: QuestionMKR Org Logo -->
            <div class="flex h-20 items-center justify-between border-b border-slate-800 px-6">
                <a href="{{ route('admin.dashboard') }}" class="focus:outline-none">
                    <x-logo :dark="true" />
                </a>
                <button
                    type="button"
                    class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden"
                    onclick="toggleAdminSidebar()"
                    aria-label="সাইডবার বন্ধ করুন"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Organization Banner -->
            <div class="border-b border-slate-800/80 bg-slate-950/40 px-6 py-3.5">
                <p class="text-[11px] font-medium tracking-wider text-slate-400 uppercase">প্রতিষ্ঠান</p>
                <p class="mt-0.5 truncate text-sm font-semibold text-amber-400">
                    {{ $admin?->organization_name ?? 'QuestionMKR HQ' }}
                </p>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1.5 overflow-y-auto px-4 py-5">
                <p class="px-3 pb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">
                    মেনু তালিকা
                </p>

                <!-- Dashboard -->
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>ড্যাশবোর্ড</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.dashboard') ? 'text-indigo-200' : 'text-slate-500' }}">Dashboard</span>
                    </div>
                </a>

                <!-- Classes -->
                <a
                    href="{{ route('admin.classes.index') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.classes.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>শ্রেণিসমূহ</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.classes.*') ? 'text-indigo-200' : 'text-slate-500' }}">Classes</span>
                    </div>
                </a>

                <!-- Subjects -->
                <a
                    href="{{ route('admin.subjects.index') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.subjects.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>বিষয়সমূহ</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.subjects.*') ? 'text-indigo-200' : 'text-slate-500' }}">Subjects</span>
                    </div>
                </a>

                <!-- Chapters -->
                <a
                    href="{{ route('admin.chapters.index') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.chapters.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.chapters.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>অধ্যায়সমূহ</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.chapters.*') ? 'text-indigo-200' : 'text-slate-500' }}">Chapters</span>
                    </div>
                </a>

                <!-- Nested Questions Menu (3 Types) -->
                <div class="space-y-1">
                    <button
                        type="button"
                        onclick="toggleQuestionsSubmenu()"
                        aria-expanded="{{ $isQuestionsActive ? 'true' : 'false' }}"
                        class="group flex w-full cursor-pointer items-center gap-3.5 rounded-xl px-3.5 py-3 text-left text-sm font-medium transition {{ $isQuestionsActive ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 shrink-0 {{ $isQuestionsActive ? 'text-amber-400' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex flex-1 items-center justify-between">
                            <span>প্রশ্নভাণ্ডার</span>
                            <svg
                                id="questions-submenu-chevron"
                                class="h-4 w-4 text-slate-400 transition-transform duration-200 {{ $isQuestionsActive ? 'rotate-180 text-white' : '' }}"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <!-- 3 Question Type Sub-links -->
                    <div
                        id="questions-submenu"
                        class="space-y-1 pl-9 {{ $isQuestionsActive ? 'block' : 'hidden' }}"
                    >
                        <a
                            href="{{ route('admin.questions.short.index') }}"
                            class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.questions.short.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800/70 hover:text-white' }}"
                        >
                            <span>সংক্ষিপ্ত প্রশ্ন</span>
                            <span class="text-[10px] opacity-75">Short</span>
                        </a>

                        <a
                            href="{{ route('admin.questions.mcq.index') }}"
                            class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.questions.mcq.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800/70 hover:text-white' }}"
                        >
                            <span>বহুনির্বাচনি প্রশ্ন</span>
                            <span class="text-[10px] opacity-75">MCQ</span>
                        </a>

                        <a
                            href="{{ route('admin.questions.creative.index') }}"
                            class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition {{ request()->routeIs('admin.questions.creative.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800/70 hover:text-white' }}"
                        >
                            <span>সৃজনশীল প্রশ্ন</span>
                            <span class="text-[10px] opacity-75">Creative</span>
                        </a>
                    </div>
                </div>

                <!-- Users -->
                <a
                    href="{{ route('admin.users.index') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>ব্যবহারকারীগণ</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.users.*') ? 'text-indigo-200' : 'text-slate-500' }}">Users</span>
                    </div>
                </a>

                <!-- My Organization -->
                <a
                    href="{{ route('admin.organization') }}"
                    class="group flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ request()->routeIs('admin.organization*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.organization*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <div class="flex flex-1 items-center justify-between">
                        <span>আমার প্রতিষ্ঠান</span>
                        <span class="text-[11px] {{ request()->routeIs('admin.organization*') ? 'text-indigo-200' : 'text-slate-500' }}">My Organization</span>
                    </div>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-slate-800 p-4">
                <div class="flex items-center gap-3 rounded-xl bg-slate-800/60 px-3.5 py-3">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-semibold text-slate-200">অ্যাডমিন প্যানেল সক্রিয়</p>
                        <p class="truncate text-[11px] text-slate-400">সংস্করণ ১.০ (বাংলা)</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex min-w-0 flex-1 flex-col">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-2xs sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        onclick="toggleAdminSidebar()"
                        class="rounded-xl border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 lg:hidden"
                        aria-label="সাইডবার খুলুন"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-xl font-bold text-slate-900">{{ $heading ?? $title }}</h1>
                        <p class="text-xs text-slate-500">QuestionMKR অ্যাডমিন ব্যবস্থাপনা পোর্টাল</p>
                    </div>
                </div>

                <!-- Right: Admin Photo + Popover -->
                <div class="relative" id="profile-popover-container">
                    <button
                        type="button"
                        id="profile-photo-button"
                        onclick="toggleProfilePopover(event)"
                        aria-expanded="false"
                        aria-haspopup="true"
                        class="group flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 p-1.5 pr-3.5 transition hover:border-indigo-300 hover:bg-indigo-50/50 focus:ring-2 focus:ring-indigo-500/30 focus:outline-none"
                    >
                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $admin?->organization_name ?? 'Admin' }}"
                            class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-600/20"
                        >
                        <div class="hidden text-left sm:block">
                            <p class="max-w-36 truncate text-sm font-semibold text-slate-800">
                                {{ $admin?->organization_name }}
                            </p>
                            <p class="text-[11px] font-medium text-indigo-600">অ্যাডমিন</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 transition group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Popover Menu -->
                    <div
                        id="profile-popover-menu"
                        class="absolute right-0 mt-2.5 hidden w-64 origin-top-right rounded-2xl border border-slate-200 bg-white p-2 shadow-xl ring-1 ring-slate-900/5"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="profile-photo-button"
                    >
                        <div class="border-b border-slate-100 px-3.5 py-3">
                            <p class="truncate text-sm font-bold text-slate-900">{{ $admin?->organization_name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $admin?->email }}</p>
                        </div>

                        <div class="py-1.5">
                            <a
                                href="{{ route('admin.organization') }}"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-700"
                                role="menuitem"
                            >
                                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div>
                                    <span class="block font-semibold">আমার প্রতিষ্ঠান</span>
                                    <span class="block text-[11px] text-slate-400">My Organization</span>
                                </div>
                            </a>
                        </div>

                        <div class="border-t border-slate-100 pt-1.5">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                                    role="menuitem"
                                >
                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <div>
                                        <span class="block font-semibold">লগআউট</span>
                                        <span class="block text-[11px] text-red-400">Logout</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function toggleQuestionsSubmenu() {
            const submenu = document.getElementById('questions-submenu');
            const chevron = document.getElementById('questions-submenu-chevron');
            submenu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function toggleProfilePopover(event) {
            event.stopPropagation();
            const menu = document.getElementById('profile-popover-menu');
            const button = document.getElementById('profile-photo-button');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden');
                button.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        }

        document.addEventListener('click', function (event) {
            const container = document.getElementById('profile-popover-container');
            const menu = document.getElementById('profile-popover-menu');
            const button = document.getElementById('profile-photo-button');

            if (container && !container.contains(event.target)) {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                const menu = document.getElementById('profile-popover-menu');
                const button = document.getElementById('profile-photo-button');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    button.setAttribute('aria-expanded', 'false');
                }
            }
        });
    </script>
</body>
</html>
