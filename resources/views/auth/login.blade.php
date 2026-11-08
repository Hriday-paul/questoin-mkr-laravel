<x-layouts.app title="ব্যবহারকারী লগইন — QuestionMKR">
    <div class="mx-auto max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">ব্যবহারকারী লগইন</h1>
            <p class="mt-1 text-sm text-slate-600">আপনার প্রতিষ্ঠানের অ্যাকাউন্টে প্রবেশ করুন।</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">ইমেইল ঠিকানা</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">পাসওয়ার্ড</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember" name="remember" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    আমাকে মনে রাখুন
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-600/30 focus:outline-none"
            >
                লগইন করুন
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            কোনো অ্যাকাউন্ট নেই?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">নতুন অ্যাকাউন্ট খুলুন</a>
        </p>
    </div>
</x-layouts.app>
