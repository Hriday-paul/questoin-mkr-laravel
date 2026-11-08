<x-layouts.admin title="শ্রেণিসমূহ (Classes)" heading="শ্রেণিসমূহ ব্যবস্থাপনা">
    <div class="space-y-6">
        <!-- Top Bar: Add New Class Card -->
        <div class="relative overflow-hidden rounded-2xl border border-indigo-100 bg-gradient-to-br from-white via-white to-indigo-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-600/25">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">নতুন শ্রেণি যোগ করুন</h2>
                        <p class="mt-0.5 text-xs text-slate-500">আপনার প্রতিষ্ঠানের জন্য নতুন শ্রেণির নাম লিখে যুক্ত করুন।</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.classes.store') }}" class="flex w-full flex-col gap-3 sm:flex-row lg:max-w-lg">
                    @csrf
                    <div class="flex-1">
                        <label for="name" class="sr-only">শ্রেণির নাম</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            placeholder="শ্রেণির নাম লিখুন (যেমন: ৬ষ্ঠ শ্রেণি)..."
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-900 shadow-2xs transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-500 active:scale-[0.99]"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>যুক্ত করুন</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Redesigned Classes Table Card -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <!-- Table Header Bar -->
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">সকল শ্রেণির তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-bold text-indigo-700 ring-1 ring-indigo-600/15">
                        মোট {{ $classes->count() }} টি
                    </span>
                </div>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-24 px-6 py-4">ক্রমিক নং</th>
                            <th class="px-6 py-4">শ্রেণির নাম</th>
                            <th class="w-36 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($classes as $index => $classItem)
                            <tr class="group transition-colors duration-150 hover:bg-indigo-50/35">
                                <!-- Serial Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                        {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <!-- Class Name Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-500/10">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <span class="text-base font-bold text-slate-800 group-hover:text-indigo-950">
                                            {{ $classItem->name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Actions Column (Icons with Tooltips) -->
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Edit Icon Button with Tooltip -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openEditClassModal({{ $classItem->id }}, @js($classItem->name), @js(route('admin.classes.update', $classItem)))"
                                                aria-label="সম্পাদনা করুন"
                                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-slate-900 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all duration-150 group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                সম্পাদনা করুন
                                                <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></span>
                                            </span>
                                        </div>

                                        <!-- Delete Icon Button with Tooltip (Opens Custom Dialog) -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openDeleteClassModal(@js($classItem->name), @js(route('admin.classes.destroy', $classItem)))"
                                                aria-label="মুছে ফেলুন"
                                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600 focus:ring-2 focus:ring-rose-500/20 focus:outline-none"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-rose-600 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all duration-150 group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                মুছে ফেলুন
                                                <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-rose-600"></span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-14 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-slate-700">এখনো কোনো শ্রেণি যোগ করা হয়নি</p>
                                    <p class="mt-1 text-xs text-slate-500">উপরের বক্সে শ্রেণির নাম লিখে প্রথম শ্রেণি যুক্ত করুন।</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Class Dialog Modal -->
    <div
        id="edit-class-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        role="dialog"
        aria-modal="true"
        aria-labelledby="edit-modal-title"
    >
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl"
            onclick="event.stopPropagation()"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="edit-modal-title" class="text-base font-bold text-slate-900">শ্রেণির নাম সম্পাদনা</h3>
                        <p class="text-xs text-slate-500">পরিবর্তন করে আপডেট বাটনে ক্লিক করুন</p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeEditClassModal()"
                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    aria-label="বন্ধ করুন"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-class-form" method="POST" action="" class="mt-5 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit-class-name" class="block text-sm font-semibold text-slate-700">
                        শ্রেণির নাম
                    </label>
                    <input
                        id="edit-class-name"
                        name="name"
                        type="text"
                        required
                        class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <button
                        type="button"
                        onclick="closeEditClassModal()"
                        class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        বাতিল করুন
                    </button>
                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-500"
                    >
                        আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Dialog Modal (No browser alert) -->
    <div
        id="delete-class-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="delete-modal-title"
        aria-describedby="delete-modal-description"
        onclick="closeDeleteClassModal()"
    >
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl"
            onclick="event.stopPropagation()"
        >
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-500/15">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 id="delete-modal-title" class="text-lg font-bold text-slate-900">
                        আপনি কি নিশ্চিতভাবে মুছে ফেলতে চান?
                    </h3>
                    <p id="delete-modal-description" class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-class-name-label" class="font-bold text-slate-900"></span> শ্রেণিটি মুছে ফেলতে যাচ্ছেন। এই কাজটি পূর্বাবস্থায় ফিরিয়ে আনা যাবে না।
                    </p>
                </div>
            </div>

            <form id="delete-class-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')

                <button
                    type="button"
                    onclick="closeDeleteClassModal()"
                    class="cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    না, বাতিল করুন
                </button>
                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-rose-600/20 transition hover:bg-rose-500"
                >
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditClassModal(id, name, updateUrl) {
            const modal = document.getElementById('edit-class-modal');
            const form = document.getElementById('edit-class-form');
            const input = document.getElementById('edit-class-name');

            form.action = updateUrl;
            input.value = name;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            input.focus();
        }

        function closeEditClassModal() {
            const modal = document.getElementById('edit-class-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteClassModal(name, deleteUrl) {
            const modal = document.getElementById('delete-class-modal');
            const form = document.getElementById('delete-class-form');
            const nameLabel = document.getElementById('delete-class-name-label');

            form.action = deleteUrl;
            nameLabel.textContent = '“' + name + '”';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteClassModal() {
            const modal = document.getElementById('delete-class-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeEditClassModal();
                closeDeleteClassModal();
            }
        });
    </script>
</x-layouts.admin>
