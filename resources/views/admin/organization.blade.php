<x-layouts.admin title="আমার প্রতিষ্ঠান (My Organization)" heading="আমার প্রতিষ্ঠান">
    <div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs sm:p-8">
        <div class="flex items-center gap-4 border-b border-slate-100 pb-6">
            <x-logo />
            <div class="ml-auto text-right">
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                    প্রধান প্রতিষ্ঠান প্রোফাইল
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.organization.update') }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="organization_name" class="block text-sm font-semibold text-slate-700">
                    প্রতিষ্ঠানের নাম (Organization Name)
                </label>
                <input
                    id="organization_name"
                    name="organization_name"
                    type="text"
                    value="{{ old('organization_name', auth()->user()->organization_name) }}"
                    required
                    class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('organization_name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">
                    অ্যাডমিন ইমেইল ঠিকানা (Email Address)
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                    class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-semibold text-slate-700">
                    ফোন নম্বর (Phone Number)
                </label>
                <input
                    id="phone_number"
                    name="phone_number"
                    type="tel"
                    value="{{ old('phone_number', auth()->user()->phone_number) }}"
                    required
                    class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                @error('phone_number')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-indigo-500"
                >
                    তথ্য সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
