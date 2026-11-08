<x-layouts.admin title="ব্যবহারকারীগণ (Users)" heading="ব্যবহারকারী ও প্রতিষ্ঠান তালিকা">
    <div class="space-y-6">
        <!-- Search & Filter Bar -->
        <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white via-white to-emerald-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">সকল নিবন্ধিত ব্যবহারকারী ও প্রতিষ্ঠান</h2>
                        <p class="mt-0.5 text-xs text-slate-500">প্রতিষ্ঠানের নাম, ইমেইল বা ফোন নম্বর দিয়ে অনুসন্ধান ও পরিচালনা করুন।</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" class="flex w-full flex-col gap-3 sm:flex-row lg:max-w-lg">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="প্রতিষ্ঠান, ইমেইল বা ফোন নম্বর..."
                        class="block flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none"
                    >
                    <select
                        name="role"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none"
                    >
                        <option value="">সকল ভূমিকা</option>
                        <option value="user" @selected(request('role') === 'user')>ব্যবহারকারী (User)</option>
                        <option value="admin" @selected(request('role') === 'admin')>অ্যাডমিন (Admin)</option>
                    </select>
                    <button type="submit" class="cursor-pointer rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        খুঁজুন
                    </button>
                </form>
            </div>
        </div>

        <!-- Redesigned Users Table -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">ব্যবহারকারী তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-600/15">
                        মোট {{ $users->total() }} জন
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-20 px-6 py-4">ক্রমিক</th>
                            <th class="px-6 py-4">প্রতিষ্ঠানের নাম</th>
                            <th class="px-6 py-4">ইমেইল ও ফোন নম্বর</th>
                            <th class="px-6 py-4">ভূমিকা</th>
                            <th class="px-6 py-4">ইমেইল স্ট্যাটাস</th>
                            <th class="w-36 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($users as $index => $user)
                            <tr class="group transition-colors duration-150 hover:bg-emerald-50/25">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 group-hover:bg-emerald-600 group-hover:text-white">
                                        {{ str_pad((string) ($users->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $user->organization_name }}
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $user->email }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->phone_number }}</p>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->isAdmin())
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                            অ্যাডমিন
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                                            ব্যবহারকারী
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->hasVerifiedEmail())
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                            যাচাইকৃত
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                            অযাচাইকৃত
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        @if (! $user->hasVerifiedEmail())
                                            <div class="group/tooltip relative inline-block">
                                                <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        aria-label="ইমেইল যাচাই করুন"
                                                        class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600 shadow-2xs transition hover:border-emerald-300 hover:bg-emerald-50"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-slate-900 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                    ইমেইল যাচাই করুন
                                                </span>
                                            </div>
                                        @endif

                                        @if (! auth()->user()->is($user))
                                            <div class="group/tooltip relative inline-block">
                                                <button
                                                    type="button"
                                                    onclick="openDeleteUserModal(@js($user->organization_name), @js(route('admin.users.destroy', $user)))"
                                                    aria-label="মুছে ফেলুন"
                                                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-rose-600 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                    মুছে ফেলুন
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    কোনো ব্যবহারকারী পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Delete User Confirmation Modal -->
    <div
        id="delete-user-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeDeleteUserModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">ব্যবহারকারী মুছে ফেলতে চান?</h3>
                    <p class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-user-label" class="font-bold text-slate-900"></span> অ্যাকাউন্টটি মুছে ফেলতে যাচ্ছেন।
                    </p>
                </div>
            </div>

            <form id="delete-user-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteUserModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    না, বাতিল করুন
                </button>
                <button type="submit" class="cursor-pointer rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </form>
        </div>
    </div>

    <script>
        function openDeleteUserModal(name, deleteUrl) {
            document.getElementById('delete-user-form').action = deleteUrl;
            document.getElementById('delete-user-label').textContent = '“' + name + '”';
            const modal = document.getElementById('delete-user-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteUserModal() {
            const modal = document.getElementById('delete-user-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-layouts.admin>
