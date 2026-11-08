<x-layouts.admin title="সংক্ষিপ্ত প্রশ্ন (Short Questions)" heading="সংক্ষিপ্ত প্রশ্ন ব্যবস্থাপনা">
    <div class="space-y-6">
        <!-- Top Header & Search/Filter Bar -->
        <div class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-white via-white to-indigo-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-600/25">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">সংক্ষিপ্ত প্রশ্নভাণ্ডার (Short Questions)</h2>
                        <p class="mt-0.5 text-xs text-slate-500">এক লাইনের সংক্ষিপ্ত প্রশ্ন, নম্বর, উত্তর এবং ঐচ্ছিক চিত্র যোগ ও পরিচালনা করুন।</p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openShortModal()"
                    class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-500"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>নতুন সংক্ষিপ্ত প্রশ্ন</span>
                </button>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.questions.short.index') }}" class="mt-5 grid grid-cols-1 gap-3 border-t border-slate-100 pt-5 sm:grid-cols-12">
                <div class="sm:col-span-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="প্রশ্ন বা উত্তর লিখে খুঁজুন..."
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div class="sm:col-span-2">
                    <select
                        name="class_id"
                        id="filter-class"
                        onchange="cascadeSubjects('filter-class', 'filter-subject', 'filter-chapter')"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="">সকল শ্রেণি</option>
                        @foreach ($classes as $classItem)
                            <option value="{{ $classItem->id }}" @selected(request('class_id') == $classItem->id)>{{ $classItem->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select
                        name="subject_id"
                        id="filter-subject"
                        onchange="cascadeChapters('filter-subject', 'filter-chapter')"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="">সকল বিষয়</option>
                        @foreach ($subjects as $subjectItem)
                            <option value="{{ $subjectItem->id }}" data-class-id="{{ $subjectItem->class_id }}" @selected(request('subject_id') == $subjectItem->id)>
                                {{ $subjectItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select
                        name="chapter_id"
                        id="filter-chapter"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="">সকল অধ্যায়</option>
                        @foreach ($chapters as $chapterItem)
                            <option value="{{ $chapterItem->id }}" data-subject-id="{{ $chapterItem->subject_id }}" @selected(request('chapter_id') == $chapterItem->id)>
                                {{ $chapterItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 sm:col-span-2">
                    <button type="submit" class="flex-1 cursor-pointer rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        খুঁজুন
                    </button>
                    <a href="{{ route('admin.questions.short.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        রিসেট
                    </a>
                </div>
            </form>
        </div>

        <!-- Questions Table Card -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">সংক্ষিপ্ত প্রশ্ন তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-bold text-indigo-700 ring-1 ring-indigo-600/15">
                        মোট {{ $questions->total() }} টি
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-20 px-6 py-4">ক্রমিক</th>
                            <th class="px-6 py-4">প্রশ্ন, চিত্র ও উত্তর</th>
                            <th class="w-60 px-6 py-4">শ্রেণি / বিষয় / অধ্যায়</th>
                            <th class="w-24 px-6 py-4 text-center">নম্বর</th>
                            <th class="w-32 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($questions as $index => $q)
                            <tr class="group transition-colors duration-150 hover:bg-indigo-50/30">
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 group-hover:bg-indigo-600 group-hover:text-white">
                                        {{ str_pad((string) ($questions->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <!-- If figure_on_top is true, show figure BEFORE question -->
                                    @if ($q->figure && $q->figure_on_top)
                                        <div class="mb-2.5 inline-block rounded-xl border border-slate-200 bg-slate-50 p-2">
                                            <div class="mb-1 flex items-center justify-between gap-3">
                                                <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">চিত্র (প্রশ্নের উপরে)</span>
                                                <button
                                                    type="button"
                                                    onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'short', 'id' => $q->id, 'field' => 'figure'])))"
                                                    class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                >
                                                    চিত্র মুছুন ✕
                                                </button>
                                            </div>
                                            <img src="{{ $q->figure }}" alt="প্রশ্ন চিত্র" class="max-h-36 rounded-lg object-contain">
                                        </div>
                                    @endif

                                    <p class="text-base font-bold text-slate-900">{{ $q->question }}</p>

                                    <!-- If figure_on_top is false, show figure AFTER question -->
                                    @if ($q->figure && ! $q->figure_on_top)
                                        <div class="mt-2.5 inline-block rounded-xl border border-slate-200 bg-slate-50 p-2">
                                            <div class="mb-1 flex items-center justify-between gap-3">
                                                <span class="rounded bg-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-700">চিত্র (প্রশ্নের নিচে)</span>
                                                <button
                                                    type="button"
                                                    onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'short', 'id' => $q->id, 'field' => 'figure'])))"
                                                    class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                >
                                                    চিত্র মুছুন ✕
                                                </button>
                                            </div>
                                            <img src="{{ $q->figure }}" alt="প্রশ্ন চিত্র" class="max-h-36 rounded-lg object-contain">
                                        </div>
                                    @endif

                                    <div class="mt-2 rounded-xl bg-emerald-50/70 px-3.5 py-2 text-xs text-emerald-900 ring-1 ring-emerald-500/15">
                                        <span class="font-bold">উত্তর:</span> {{ $q->answer }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                                            {{ $q->academicClass?->name }}
                                        </span>
                                        <span class="inline-flex w-fit items-center rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-bold text-violet-700">
                                            {{ $q->subject?->name }}
                                        </span>
                                        <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                                            {{ $q->chapter?->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center align-top whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-800">
                                        {{ $q->mark }} নম্বর
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right align-top whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Edit Icon with Tooltip -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openEditShortModal(@js($q), @js(route('admin.questions.short.update', $q)))"
                                                aria-label="সম্পাদনা করুন"
                                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-slate-900 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                সম্পাদনা করুন
                                            </span>
                                        </div>

                                        <!-- Delete Icon with Tooltip -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openDeleteQuestionModal(@js($q->question), @js(route('admin.questions.short.destroy', $q)))"
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <p class="text-sm font-semibold text-slate-700">কোনো সংক্ষিপ্ত প্রশ্ন পাওয়া যায়নি</p>
                                    <p class="mt-1 text-xs text-slate-500">উপরে ডানপাশে “নতুন সংক্ষিপ্ত প্রশ্ন” বাটনে ক্লিক করে প্রশ্ন যুক্ত করুন।</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($questions->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add / Edit Short Question Modal -->
    <div
        id="short-question-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeShortModal()"
    >
        <div class="my-8 w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 id="short-modal-title" class="text-lg font-bold text-slate-900">নতুন সংক্ষিপ্ত প্রশ্ন যোগ করুন</h3>
                <button type="button" onclick="closeShortModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">✕</button>
            </div>

            <form id="short-question-form" method="POST" action="{{ route('admin.questions.short.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" id="short-form-method" name="_method" value="POST">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700">শ্রেণি *</label>
                        <select
                            id="modal-class"
                            name="class_id"
                            required
                            onchange="cascadeSubjects('modal-class', 'modal-subject', 'modal-chapter')"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm"
                        >
                            <option value="">নির্বাচন করুন</option>
                            @foreach ($classes as $classItem)
                                <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700">বিষয় *</label>
                        <select
                            id="modal-subject"
                            name="subject_id"
                            required
                            onchange="cascadeChapters('modal-subject', 'modal-chapter')"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm"
                        >
                            <option value="">নির্বাচন করুন</option>
                            @foreach ($subjects as $subjectItem)
                                <option value="{{ $subjectItem->id }}" data-class-id="{{ $subjectItem->class_id }}">{{ $subjectItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700">অধ্যায় *</label>
                        <select
                            id="modal-chapter"
                            name="chapter_id"
                            required
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm"
                        >
                            <option value="">নির্বাচন করুন</option>
                            @foreach ($chapters as $chapterItem)
                                <option value="{{ $chapterItem->id }}" data-subject-id="{{ $chapterItem->subject_id }}">{{ $chapterItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700">সংক্ষিপ্ত প্রশ্ন (এক লাইনে) *</label>
                        <input
                            id="modal-question"
                            type="text"
                            name="question"
                            required
                            placeholder="প্রশ্নটি লিখুন..."
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700">নম্বর (Mark) *</label>
                        <input
                            id="modal-mark"
                            type="number"
                            name="mark"
                            value="2"
                            min="1"
                            required
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">উত্তর (Answer) *</label>
                    <textarea
                        id="modal-answer"
                        name="answer"
                        rows="3"
                        required
                        placeholder="প্রশ্নের উত্তর লিখুন..."
                        class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                    ></textarea>
                </div>

                <!-- Optional Figure & figureOnTop Option + Remove Existing Figure -->
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:items-center">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">চিত্র যোগ/পরিবর্তন করুন (ঐচ্ছিক / Figure)</label>
                            <input
                                type="file"
                                name="figure_file"
                                accept="image/*"
                                class="mt-1.5 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-indigo-500"
                            >
                        </div>

                        <div class="space-y-2 pt-2 sm:pt-4">
                            <label class="inline-flex cursor-pointer items-center gap-2.5 text-sm font-semibold text-slate-700">
                                <input
                                    id="modal-figure-on-top"
                                    type="checkbox"
                                    name="figure_on_top"
                                    value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <span>চিত্রটি প্রশ্নের উপরে দেখান (figureOnTop)</span>
                            </label>

                            <div id="modal-remove-figure-wrap" class="hidden">
                                <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-bold text-rose-600">
                                    <input
                                        id="modal-remove-figure"
                                        type="checkbox"
                                        name="remove_figure"
                                        value="1"
                                        class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500"
                                    >
                                    <span>বর্তমান চিত্রটি মুছে ফেলুন (Delete Figure)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeShortModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="cursor-pointer rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                        সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Question Confirmation Dialog -->
    <div
        id="delete-question-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeDeleteQuestionModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 id="delete-modal-heading" class="text-lg font-bold text-slate-900">প্রশ্নটি মুছে ফেলতে চান?</h3>
                    <p class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-question-label" class="font-bold text-slate-900"></span> মুছে ফেলতে যাচ্ছেন।
                    </p>
                </div>
            </div>

            <form id="delete-question-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteQuestionModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    না, বাতিল করুন
                </button>
                <button type="submit" class="cursor-pointer rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </form>
        </div>
    </div>

    <script>
        function cascadeSubjects(classSelectId, subjectSelectId, chapterSelectId, selectedSubject = null, selectedChapter = null) {
            const classId = document.getElementById(classSelectId).value;
            const subjectSelect = document.getElementById(subjectSelectId);
            subjectSelect.querySelectorAll('option[data-class-id]').forEach((opt) => {
                const match = !classId || opt.getAttribute('data-class-id') === String(classId);
                opt.hidden = !match;
                opt.disabled = !match;
            });
            subjectSelect.value = selectedSubject || '';
            cascadeChapters(subjectSelectId, chapterSelectId, selectedChapter);
        }

        function cascadeChapters(subjectSelectId, chapterSelectId, selectedChapter = null) {
            const subjectId = document.getElementById(subjectSelectId).value;
            const chapterSelect = document.getElementById(chapterSelectId);
            chapterSelect.querySelectorAll('option[data-subject-id]').forEach((opt) => {
                const match = !subjectId || opt.getAttribute('data-subject-id') === String(subjectId);
                opt.hidden = !match;
                opt.disabled = !match;
            });
            chapterSelect.value = selectedChapter || '';
        }

        function openShortModal() {
            document.getElementById('short-modal-title').textContent = 'নতুন সংক্ষিপ্ত প্রশ্ন যোগ করুন';
            document.getElementById('short-question-form').action = @js(route('admin.questions.short.store'));
            document.getElementById('short-form-method').value = 'POST';
            document.getElementById('modal-class').value = '';
            document.getElementById('modal-subject').value = '';
            document.getElementById('modal-chapter').value = '';
            document.getElementById('modal-question').value = '';
            document.getElementById('modal-mark').value = '2';
            document.getElementById('modal-answer').value = '';
            document.getElementById('modal-figure-on-top').checked = false;
            document.getElementById('modal-remove-figure').checked = false;
            document.getElementById('modal-remove-figure-wrap').classList.add('hidden');

            const modal = document.getElementById('short-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditShortModal(q, updateUrl) {
            document.getElementById('short-modal-title').textContent = 'সংক্ষিপ্ত প্রশ্ন সম্পাদনা করুন';
            document.getElementById('short-question-form').action = updateUrl;
            document.getElementById('short-form-method').value = 'PUT';
            document.getElementById('modal-class').value = q.class_id;
            cascadeSubjects('modal-class', 'modal-subject', 'modal-chapter', q.subject_id, q.chapter_id);
            document.getElementById('modal-question').value = q.question;
            document.getElementById('modal-mark').value = q.mark;
            document.getElementById('modal-answer').value = q.answer;
            document.getElementById('modal-figure-on-top').checked = Boolean(q.figure_on_top);
            document.getElementById('modal-remove-figure').checked = false;

            const removeWrap = document.getElementById('modal-remove-figure-wrap');
            if (q.figure) {
                removeWrap.classList.remove('hidden');
            } else {
                removeWrap.classList.add('hidden');
            }

            const modal = document.getElementById('short-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeShortModal() {
            const modal = document.getElementById('short-question-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteQuestionModal(title, deleteUrl) {
            document.getElementById('delete-modal-heading').textContent = 'প্রশ্নটি মুছে ফেলতে চান?';
            document.getElementById('delete-question-form').action = deleteUrl;
            document.getElementById('delete-question-label').textContent = '“' + title + '” প্রশ্নটি';
            const modal = document.getElementById('delete-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openDeleteFigureModal(deleteFigureUrl) {
            document.getElementById('delete-modal-heading').textContent = 'চিত্রটি মুছে ফেলতে চান?';
            document.getElementById('delete-question-form').action = deleteFigureUrl;
            document.getElementById('delete-question-label').textContent = 'এই প্রশ্নের সংযুক্ত চিত্রটি';
            const modal = document.getElementById('delete-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteQuestionModal() {
            const modal = document.getElementById('delete-question-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-layouts.admin>
