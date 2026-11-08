<x-layouts.admin title="অধ্যায়সমূহ (Chapters)" heading="অধ্যায়সমূহ ব্যবস্থাপনা">
    <div class="space-y-6">
        <!-- Top Bar: Add New Chapter Card -->
        <div class="relative overflow-hidden rounded-2xl border border-amber-100 bg-gradient-to-br from-white via-white to-amber-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">নতুন অধ্যায় যোগ করুন</h2>
                        <p class="mt-0.5 text-xs text-slate-500">শ্রেণি ও বিষয় নির্বাচন করে নতুন অধ্যায়ের নাম যুক্ত করুন।</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.chapters.store') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-12">
                    @csrf

                    <!-- Class Dropdown -->
                    <div class="sm:col-span-3">
                        <label for="create-chapter-class-id" class="sr-only">শ্রেণি</label>
                        <select
                            id="create-chapter-class-id"
                            name="class_id"
                            required
                            onchange="filterSubjectOptions('create-chapter-class-id', 'create-chapter-subject-id')"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-900 shadow-2xs transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                        >
                            <option value="">— শ্রেণি নির্বাচন —</option>
                            @foreach ($classes as $classItem)
                                <option value="{{ $classItem->id }}" @selected(old('class_id') == $classItem->id)>
                                    {{ $classItem->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Dropdown -->
                    <div class="sm:col-span-3">
                        <label for="create-chapter-subject-id" class="sr-only">বিষয়</label>
                        <select
                            id="create-chapter-subject-id"
                            name="subject_id"
                            required
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-900 shadow-2xs transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                        >
                            <option value="">— বিষয় নির্বাচন —</option>
                            @foreach ($subjects as $subjectItem)
                                <option
                                    value="{{ $subjectItem->id }}"
                                    data-class-id="{{ $subjectItem->class_id }}"
                                    @selected(old('subject_id') == $subjectItem->id)
                                >
                                    {{ $subjectItem->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chapter Name -->
                    <div class="sm:col-span-4">
                        <label for="name" class="sr-only">অধ্যায়ের নাম</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            placeholder="অধ্যায়ের নাম লিখুন (যেমন: ১ম অধ্যায়: গতি)..."
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-900 shadow-2xs transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="sm:col-span-2">
                        <button
                            type="submit"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-500 active:scale-[0.99]"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>যুক্ত করুন</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Chapters Table Card -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex flex-col gap-4 border-b border-slate-100 bg-slate-50/60 px-6 py-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">সকল অধ্যায়ের তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-0.5 text-xs font-bold text-amber-800 ring-1 ring-amber-600/20">
                        মোট {{ $chapters->total() }} টি
                    </span>
                </div>

                <!-- Filter & Search Form -->
                <form method="GET" action="{{ route('admin.chapters.index') }}" class="grid grid-cols-1 gap-2.5 sm:grid-cols-12 xl:w-auto xl:min-w-[680px]">
                    <div class="sm:col-span-4">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="অধ্যায়ের নাম লিখে খুঁজুন..."
                            class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 shadow-2xs transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 focus:outline-none"
                        >
                    </div>

                    <div class="sm:col-span-3">
                        <select
                            id="filter-chapter-class-id"
                            name="class_id"
                            onchange="filterTableSubjectOptions()"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-2xs transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 focus:outline-none"
                        >
                            <option value="">সকল শ্রেণি</option>
                            @foreach ($classes as $classItem)
                                <option value="{{ $classItem->id }}" @selected(request('class_id') == $classItem->id)>
                                    {{ $classItem->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <select
                            id="filter-chapter-subject-id"
                            name="subject_id"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-2xs transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 focus:outline-none"
                        >
                            <option value="">সকল বিষয়</option>
                            @foreach ($subjects as $subjectItem)
                                <option
                                    value="{{ $subjectItem->id }}"
                                    data-class-id="{{ $subjectItem->class_id }}"
                                    @selected(request('subject_id') == $subjectItem->id)
                                >
                                    {{ $subjectItem->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 sm:col-span-2">
                        <button
                            type="submit"
                            class="flex-1 cursor-pointer rounded-xl bg-slate-900 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            খুঁজুন
                        </button>
                        @if (request()->filled('search') || request()->filled('class_id') || request()->filled('subject_id'))
                            <a
                                href="{{ route('admin.chapters.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50"
                            >
                                রিসেট
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-24 px-6 py-4">ক্রমিক নং</th>
                            <th class="px-6 py-4">অধ্যায়ের নাম</th>
                            <th class="px-6 py-4">শ্রেণির নাম</th>
                            <th class="px-6 py-4">বিষয়ের নাম</th>
                            <th class="w-36 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($chapters as $index => $chapter)
                            <tr class="group transition-colors duration-150 hover:bg-amber-50/35">
                                <!-- Serial -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 transition group-hover:bg-indigo-600 group-hover:text-white">
                                        {{ str_pad((string) ($chapters->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <!-- Chapter Name -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-500/15">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-base font-bold text-slate-800">
                                            {{ $chapter->name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Class Relation Badge -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700 ring-1 ring-indigo-600/15">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                                        {{ $chapter->academicClass?->name ?? '—' }}
                                    </span>
                                </td>

                                <!-- Subject Relation Badge -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-xs font-bold text-violet-700 ring-1 ring-violet-600/15">
                                        <span class="h-1.5 w-1.5 rounded-full bg-violet-600"></span>
                                        {{ $chapter->subject?->name ?? '—' }}
                                    </span>
                                </td>

                                <!-- Actions (Icons with Tooltips) -->
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Edit Icon Button -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openEditChapterModal(@js($chapter->name), {{ $chapter->class_id }}, {{ $chapter->subject_id }}, @js(route('admin.chapters.update', $chapter)))"
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

                                        <!-- Delete Icon Button -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openDeleteChapterModal(@js($chapter->name), @js(route('admin.chapters.destroy', $chapter)))"
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
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <p class="text-sm font-semibold text-slate-700">কোনো অধ্যায় পাওয়া যায়নি</p>
                                    <p class="mt-1 text-xs text-slate-500">ফিল্টার পরিবর্তন করুন অথবা উপরের ফর্ম থেকে নতুন অধ্যায় যুক্ত করুন।</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($chapters->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $chapters->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Chapter Modal -->
    <div
        id="edit-chapter-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        role="dialog"
        aria-modal="true"
        onclick="closeEditChapterModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-base font-bold text-slate-900">অধ্যায় সম্পাদনা করুন</h3>
                <button type="button" onclick="closeEditChapterModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    ✕
                </button>
            </div>

            <form id="edit-chapter-form" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit-chapter-class-id" class="block text-sm font-semibold text-slate-700">শ্রেণি নির্বাচন করুন</label>
                    <select
                        id="edit-chapter-class-id"
                        name="class_id"
                        required
                        onchange="filterSubjectOptions('edit-chapter-class-id', 'edit-chapter-subject-id')"
                        class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        @foreach ($classes as $classItem)
                            <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit-chapter-subject-id" class="block text-sm font-semibold text-slate-700">বিষয় নির্বাচন করুন</label>
                    <select
                        id="edit-chapter-subject-id"
                        name="subject_id"
                        required
                        class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        @foreach ($subjects as $subjectItem)
                            <option value="{{ $subjectItem->id }}" data-class-id="{{ $subjectItem->class_id }}">
                                {{ $subjectItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit-chapter-name" class="block text-sm font-semibold text-slate-700">অধ্যায়ের নাম</label>
                    <input
                        id="edit-chapter-name"
                        name="name"
                        type="text"
                        required
                        class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditChapterModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="cursor-pointer rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                        আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div
        id="delete-chapter-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        role="alertdialog"
        aria-modal="true"
        onclick="closeDeleteChapterModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-500/15">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900">আপনি কি নিশ্চিতভাবে মুছে ফেলতে চান?</h3>
                    <p class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-chapter-name-label" class="font-bold text-slate-900"></span> অধ্যায়টি মুছে ফেলতে যাচ্ছেন। এই কাজটি পূর্বাবস্থায় ফিরিয়ে আনা যাবে না।
                    </p>
                </div>
            </div>

            <form id="delete-chapter-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteChapterModal()" class="cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    না, বাতিল করুন
                </button>
                <button type="submit" class="cursor-pointer rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </form>
        </div>
    </div>

    <script>
        function filterTableSubjectOptions() {
            const classId = document.getElementById('filter-chapter-class-id').value;
            const subjectSelect = document.getElementById('filter-chapter-subject-id');
            const options = subjectSelect.querySelectorAll('option[data-class-id]');

            options.forEach((option) => {
                const matches = !classId || option.getAttribute('data-class-id') === String(classId);
                option.hidden = !matches;
                option.disabled = !matches;
            });

            if (subjectSelect.selectedOptions[0]?.disabled) {
                subjectSelect.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterTableSubjectOptions();
        });

        function filterSubjectOptions(classSelectId, subjectSelectId, selectedSubjectId = null) {
            const classId = document.getElementById(classSelectId).value;
            const subjectSelect = document.getElementById(subjectSelectId);
            const options = subjectSelect.querySelectorAll('option[data-class-id]');

            let firstMatchingValue = '';

            options.forEach((option) => {
                const matches = !classId || option.getAttribute('data-class-id') === String(classId);
                option.hidden = !matches;
                option.disabled = !matches;
                if (matches && !firstMatchingValue) {
                    firstMatchingValue = option.value;
                }
            });

            if (selectedSubjectId) {
                subjectSelect.value = selectedSubjectId;
            } else if (subjectSelect.selectedOptions[0]?.disabled) {
                subjectSelect.value = firstMatchingValue;
            }
        }

        function openEditChapterModal(name, classId, subjectId, updateUrl) {
            const modal = document.getElementById('edit-chapter-modal');
            const form = document.getElementById('edit-chapter-form');
            const nameInput = document.getElementById('edit-chapter-name');
            const classSelect = document.getElementById('edit-chapter-class-id');

            form.action = updateUrl;
            nameInput.value = name;
            classSelect.value = classId;
            filterSubjectOptions('edit-chapter-class-id', 'edit-chapter-subject-id', subjectId);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            nameInput.focus();
        }

        function closeEditChapterModal() {
            const modal = document.getElementById('edit-chapter-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteChapterModal(name, deleteUrl) {
            const modal = document.getElementById('delete-chapter-modal');
            const form = document.getElementById('delete-chapter-form');
            const label = document.getElementById('delete-chapter-name-label');

            form.action = deleteUrl;
            label.textContent = '“' + name + '”';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteChapterModal() {
            const modal = document.getElementById('delete-chapter-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-layouts.admin>
