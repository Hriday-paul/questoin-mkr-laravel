<x-layouts.app title="নিবন্ধন করুন — QuestionMKR">
    <div class="mx-auto max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">নতুন অ্যাকাউন্ট তৈরি করুন</h1>
            <p class="mt-1 text-sm text-slate-600">আপনার প্রতিষ্ঠানের তথ্য দিয়ে নিবন্ধন সম্পন্ন করুন।</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="organization_name" class="block text-sm font-medium text-slate-700">
                    প্রতিষ্ঠানের নাম (Organization Name)
                </label>
                <input
                    id="organization_name"
                    name="organization_name"
                    type="text"
                    value="{{ old('organization_name') }}"
                    required
                    autofocus
                    autocomplete="organization"
                    placeholder="উদাহরণ: আদর্শ বিদ্যাপীঠ"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('organization_name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">
                    ইমেইল ঠিকানা (Email Address)
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="name@example.com"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-slate-700">
                    ফোন নম্বর (Phone Number)
                </label>
                <input
                    id="phone_number"
                    name="phone_number"
                    type="tel"
                    value="{{ old('phone_number') }}"
                    required
                    autocomplete="tel"
                    placeholder="01XXXXXXXXX"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('phone_number')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">
                    পাসওয়ার্ড (Password)
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                    পাসওয়ার্ড নিশ্চিত করুন (Confirm Password)
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-600/30 focus:outline-none"
            >
                নিবন্ধন করুন
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            ইতিমধ্যে অ্যাকাউন্ট আছে?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">লগইন করুন</a>
        </p>
    </div>
</x-layouts.app>
