<x-layouts.admin title="অ্যাডমিন ড্যাশবোর্ড" heading="অ্যাডমিন ড্যাশবোর্ড">
    <!-- Welcome Banner -->
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 p-6 text-white shadow-md sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-indigo-100">
                    স্বাগতম, অ্যাডমিন
                </span>
                <h2 class="mt-2 text-2xl font-bold sm:text-3xl">
                    {{ auth()->user()->organization_name }}
                </h2>
                <p class="mt-1 text-sm text-indigo-100">
                    আপনার প্রশ্ন ব্যাংক, শ্রেণি, বিষয়, অধ্যায় এবং ব্যবহারকারীদের এখান থেকে পরিচালনা করুন।
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('admin.questions.short.index') }}"
                    class="rounded-xl bg-amber-400 px-4 py-2.5 text-sm font-bold text-slate-950 shadow-xs transition hover:bg-amber-300"
                >
                    + সংক্ষিপ্ত প্রশ্ন
                </a>
                <a
                    href="{{ route('admin.questions.mcq.index') }}"
                    class="rounded-xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-xs transition hover:bg-white/25"
                >
                    + বহুনির্বাচনি (MCQ)
                </a>
                <a
                    href="{{ route('admin.questions.creative.index') }}"
                    class="rounded-xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-xs transition hover:bg-white/25"
                >
                    + সৃজনশীল (CQ)
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.classes.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs transition hover:border-indigo-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-500">মোট শ্রেণি (Classes)</span>
                <span class="rounded-xl bg-indigo-50 p-2.5 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-slate-900">{{ $totalClasses }}</p>
            <p class="mt-1 text-xs font-medium text-indigo-600">সকল শ্রেণি দেখুন →</p>
        </a>

        <a href="{{ route('admin.subjects.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs transition hover:border-violet-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-500">বিষয় ও অধ্যায়</span>
                <span class="rounded-xl bg-violet-50 p-2.5 text-violet-600 group-hover:bg-violet-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-slate-900">{{ $totalSubjects }} <span class="text-sm font-semibold text-slate-400">/ {{ $totalChapters ?? 0 }} অধ্যায়</span></p>
            <p class="mt-1 text-xs font-medium text-violet-600">বিষয় ও অধ্যায় দেখুন →</p>
        </a>

        <a href="{{ route('admin.questions.short.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs transition hover:border-amber-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-500">মোট প্রশ্ন (Questions)</span>
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600 group-hover:bg-amber-500 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-slate-900">{{ $totalQuestions }}</p>
            <p class="mt-1 text-xs font-medium text-amber-600">
                সংক্ষিপ্ত: {{ $shortCount ?? 0 }} • MCQ: {{ $mcqCount ?? 0 }} • সৃজনশীল: {{ $creativeCount ?? 0 }}
            </p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs transition hover:border-emerald-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-500">মোট ব্যবহারকারী (Users)</span>
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-slate-900">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs font-medium text-emerald-600">ব্যবহারকারী তালিকা →</p>
        </a>
    </div>

    <!-- Recent Users Table -->
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">সাম্প্রতিক নিবন্ধিত প্রতিষ্ঠান ও ব্যবহারকারী</h3>
                <p class="text-xs text-slate-500">সর্বশেষ নিবন্ধনকৃত ৫ জন ব্যবহারকারী</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                সবাইকে দেখুন →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                        <th class="px-6 py-3.5">প্রতিষ্ঠানের নাম</th>
                        <th class="px-6 py-3.5">ইমেইল</th>
                        <th class="px-6 py-3.5">ফোন নম্বর</th>
                        <th class="px-6 py-3.5">ইমেইল যাচাই</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentUsers as $user)
                        <tr class="transition hover:bg-indigo-50/30">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $user->organization_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->phone_number }}</td>
                            <td class="px-6 py-4">
                                @if ($user->hasVerifiedEmail())
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                        যাচাইকৃত
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                                        অপেক্ষমাণ
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                এখনো কোনো ব্যবহারকারী নিবন্ধিত হয়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
