<x-layouts.app title="অ্যাডমিন লগইন — QuestionMKR">
    <div class="mx-auto max-w-md rounded-2xl border border-slate-800 bg-slate-900 p-8 text-white shadow-lg">
        <div class="mb-6">
            <span class="inline-flex items-center rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-400 ring-1 ring-amber-500/30">
                অ্যাডমিন পোর্টাল
            </span>
            <h1 class="mt-3 text-2xl font-bold text-white">অ্যাডমিন লগইন</h1>
            <p class="mt-1 text-sm text-slate-400">শুধুমাত্র অনুমোদিত সিস্টেম অ্যাডমিনদের জন্য।</p>
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-200">অ্যাডমিন ইমেইল ঠিকানা</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    class="mt-1.5 block w-full rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2.5 text-sm text-white placeholder-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-200">পাসওয়ার্ড</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1.5 block w-full rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2.5 text-sm text-white placeholder-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center gap-2 text-sm text-slate-300">
                    <input id="remember" name="remember" type="checkbox" value="1" class="rounded border-slate-600 bg-slate-800 text-amber-500 focus:ring-amber-500">
                    আমাকে মনে রাখুন
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-bold text-slate-950 shadow-xs hover:bg-amber-400 focus:ring-2 focus:ring-amber-500/30 focus:outline-none"
            >
                অ্যাডমিন হিসেবে লগইন করুন
            </button>
        </form>
    </div>
</x-layouts.app>
