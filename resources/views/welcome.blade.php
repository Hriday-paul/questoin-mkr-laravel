<x-layouts.app title="QuestionMKR — স্মার্ট প্রশ্ন ব্যাংক ও পরীক্ষা ব্যবস্থাপনা">
    <div class="mx-auto max-w-2xl py-8 text-center">
        <div class="mb-6 flex justify-center">
            <x-logo />
        </div>

        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            স্মার্ট প্রশ্ন ব্যাংক ও প্রশ্নপত্র তৈরি সিস্টেম
        </h1>
        <p class="mt-3 text-base text-slate-600">
            আপনার শিক্ষাপ্রতিষ্ঠানের শ্রেণি, বিষয় এবং প্রশ্নপত্র খুব সহজে বাংলায় তৈরি ও পরিচালনা করুন।
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-slate-950 hover:bg-amber-400">
                        অ্যাডমিন পোর্টালে যান
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                        ড্যাশবোর্ডে যান
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                    প্রতিষ্ঠান নিবন্ধন করুন
                </a>
                <a href="{{ route('login') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    ব্যবহারকারী লগইন
                </a>
                <a href="{{ route('admin.login') }}" class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                    অ্যাডমিন লগইন
                </a>
            @endauth
        </div>
    </div>
</x-layouts.app>
