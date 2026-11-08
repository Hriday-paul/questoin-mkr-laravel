<x-layouts.admin title="সৃজনশীল প্রশ্ন (Creative Questions)" heading="সৃজনশীল প্রশ্ন (CQ) ব্যবস্থাপনা">
    <div class="space-y-6">
        <!-- Top Header & Search/Filter Bar -->
        <div class="rounded-2xl border border-amber-100 bg-gradient-to-br from-white via-white to-amber-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">সৃজনশীল প্রশ্নভাণ্ডার (Creative Questions)</h2>
                        <p class="mt-0.5 text-xs text-slate-500">উদ্দীপক ও সংক্ষিপ্ত সারসংক্ষেপ তালিকায় দেখুন এবং ভিউ (View) বাটনে ক্লিক করে সম্পূর্ণ প্রশ্ন দেখুন।</p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openCreativeModal()"
                    class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-500"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>নতুন সৃজনশীল প্রশ্ন</span>
                </button>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.questions.creative.index') }}" class="mt-5 grid grid-cols-1 gap-3 border-t border-slate-100 pt-5 sm:grid-cols-12">
                <div class="sm:col-span-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="উদ্দীপক, প্রশ্ন বা উত্তর ব্যাখ্যা লিখে খুঁজুন..."
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                    >
                </div>

                <div class="sm:col-span-2">
                    <select
                        name="class_id"
                        id="filter-cq-class"
                        onchange="cascadeSubjects('filter-cq-class', 'filter-cq-subject', 'filter-cq-chapter')"
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
                        id="filter-cq-subject"
                        onchange="cascadeChapters('filter-cq-subject', 'filter-cq-chapter')"
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
                        id="filter-cq-chapter"
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
                    <a href="{{ route('admin.questions.creative.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        রিসেট
                    </a>
                </div>
            </form>
        </div>

        <!-- Creative Questions Table Card (Shows Summary Only + View Full Question Button) -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">সৃজনশীল প্রশ্ন তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-0.5 text-xs font-bold text-amber-800 ring-1 ring-amber-600/20">
                        মোট {{ $questions->total() }} টি
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-20 px-6 py-4">ক্রমিক</th>
                            <th class="px-6 py-4">উদ্দীপক (সংক্ষিপ্ত অংশ)</th>
                            <th class="w-60 px-6 py-4">শ্রেণি / বিষয় / অধ্যায়</th>
                            <th class="w-28 px-6 py-4 text-center">পূর্ণমান</th>
                            <th class="w-40 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($questions as $index => $q)
                            <tr class="group transition-colors duration-150 hover:bg-amber-50/25">
                                <td class="px-6 py-4 align-middle whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 group-hover:bg-indigo-600 group-hover:text-white">
                                        {{ str_pad((string) ($questions->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <!-- Summary / Partial Preview Only -->
                                <td class="px-6 py-4 align-middle">
                                    <p class="font-bold text-slate-900">
                                        {{ \Illuminate\Support\Str::limit($q->title, 110) }}
                                    </p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] font-semibold text-slate-500">
                                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-slate-700">
                                            ক ({{ $q->mark_a }}) • খ ({{ $q->mark_b }}) • গ ({{ $q->mark_c }}) • ঘ ({{ $q->mark_d }})
                                        </span>
                                        @if ($q->figure || $q->figure_a || $q->figure_b || $q->figure_c || $q->figure_d)
                                            <span class="rounded-md bg-amber-50 px-2 py-0.5 text-amber-700">
                                                চিত্র সংযুক্ত আছে
                                            </span>
                                        @endif
                                        <button
                                            type="button"
                                            onclick="openViewCreativeModal(@js($q))"
                                            class="cursor-pointer font-bold text-indigo-600 hover:text-indigo-500 hover:underline"
                                        >
                                            সম্পূর্ণ প্রশ্ন দেখুন →
                                        </button>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <div class="flex flex-col gap-1">
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

                                <td class="px-6 py-4 text-center align-middle whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-700">
                                        {{ $q->full_mark }} নম্বর
                                    </span>
                                </td>

                                <!-- Actions: View, Edit, Delete Icons with Tooltips -->
                                <td class="px-6 py-4 text-right align-middle whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- View Full Question Icon Button -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openViewCreativeModal(@js($q))"
                                                aria-label="সম্পূর্ণ প্রশ্ন দেখুন"
                                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-emerald-700 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                সম্পূর্ণ প্রশ্ন দেখুন
                                            </span>
                                        </div>

                                        <!-- Edit Icon Button -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openEditCreativeModal(@js($q), @js(route('admin.questions.creative.update', $q)))"
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

                                        <!-- Delete Icon Button -->
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openDeleteCreativeModal(@js($q->title), @js(route('admin.questions.creative.destroy', $q)))"
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
                                    <p class="text-sm font-semibold text-slate-700">কোনো সৃজনশীল প্রশ্ন পাওয়া যায়নি</p>
                                    <p class="mt-1 text-xs text-slate-500">উপরে ডানপাশে “নতুন সৃজনশীল প্রশ্ন” বাটনে ক্লিক করে প্রশ্ন যুক্ত করুন।</p>
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

    <!-- View Full Creative Question Modal -->
    <div
        id="view-creative-modal"
        class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeViewCreativeModal()"
    >
        <div class="my-6 w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">সম্পূর্ণ সৃজনশীল প্রশ্ন</h3>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs">
                        <span id="view-cq-class" class="rounded-full bg-indigo-50 px-2.5 py-0.5 font-bold text-indigo-700"></span>
                        <span id="view-cq-subject" class="rounded-full bg-violet-50 px-2.5 py-0.5 font-bold text-violet-700"></span>
                        <span id="view-cq-chapter" class="rounded-full bg-amber-50 px-2.5 py-0.5 font-bold text-amber-800"></span>
                        <span id="view-cq-fullmark" class="rounded-full bg-emerald-50 px-2.5 py-0.5 font-extrabold text-emerald-700"></span>
                    </div>
                </div>
                <button type="button" onclick="closeViewCreativeModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">✕</button>
            </div>

            <div class="mt-5 space-y-4">
                <!-- Stem Figure Top -->
                <div id="view-cq-stem-fig-top" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">উদ্দীপকের চিত্র (উপরে)</span>
                        <button type="button" id="view-cq-del-stem-fig-top" class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-600 hover:bg-rose-100">চিত্র মুছুন ✕</button>
                    </div>
                    <img id="view-cq-stem-img-top" src="" alt="উদ্দীপকের চিত্র" class="max-h-52 rounded-lg object-contain">
                </div>

                <!-- Stem Text -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                    <span class="text-xs font-bold tracking-wider text-indigo-600 uppercase">উদ্দীপক:</span>
                    <p id="view-cq-title" class="mt-1 text-base leading-relaxed font-bold text-slate-900"></p>
                </div>

                <!-- Stem Figure Bottom -->
                <div id="view-cq-stem-fig-bottom" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="rounded bg-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-700">উদ্দীপকের চিত্র (নিচে)</span>
                        <button type="button" id="view-cq-del-stem-fig-bottom" class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-600 hover:bg-rose-100">চিত্র মুছুন ✕</button>
                    </div>
                    <img id="view-cq-stem-img-bottom" src="" alt="উদ্দীপকের চিত্র" class="max-h-52 rounded-lg object-contain">
                </div>

                <!-- 4 Sub-Questions Container -->
                <div id="view-cq-subquestions" class="space-y-3"></div>

                <!-- Answer Explanation -->
                <div id="view-cq-explanation-wrap" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 text-sm text-emerald-950">
                    <span class="font-bold">উত্তর ও ব্যাখ্যা:</span>
                    <p id="view-cq-explanation" class="mt-1 whitespace-pre-line"></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
                <button type="button" onclick="closeViewCreativeModal()" class="cursor-pointer rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    বন্ধ করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Add / Edit Creative Question Modal -->
    <div
        id="creative-question-modal"
        class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeCreativeModal()"
    >
        <div class="my-6 w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <h3 id="creative-modal-title" class="text-lg font-bold text-slate-900">নতুন সৃজনশীল প্রশ্ন যোগ করুন</h3>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-700">
                        পূর্ণমান (Full Mark): <span id="cq-total-mark-display">10</span>
                    </span>
                </div>
                <button type="button" onclick="closeCreativeModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">✕</button>
            </div>

            <form id="creative-question-form" method="POST" action="{{ route('admin.questions.creative.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" id="creative-form-method" name="_method" value="POST">

                <!-- Class, Subject, Chapter -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700">শ্রেণি *</label>
                        <select
                            id="cq-class"
                            name="class_id"
                            required
                            onchange="cascadeSubjects('cq-class', 'cq-subject', 'cq-chapter')"
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
                            id="cq-subject"
                            name="subject_id"
                            required
                            onchange="cascadeChapters('cq-subject', 'cq-chapter')"
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
                            id="cq-chapter"
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

                <!-- Stem / Title & Figure -->
                <div>
                    <label class="block text-xs font-bold text-slate-700">উদ্দীপক (Creative Stem / Title) *</label>
                    <textarea
                        id="cq-title"
                        name="title"
                        rows="3"
                        required
                        placeholder="সৃজনশীল প্রশ্নের উদ্দীপক লিখুন..."
                        class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                    ></textarea>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3.5">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:items-center">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">উদ্দীপকের চিত্র (ঐচ্ছিক)</label>
                            <input type="file" name="figure_file" accept="image/*" class="mt-1 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-700">
                                <input id="cq-figure-on-top" type="checkbox" name="figure_on_top" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                                <span>চিত্রটি উদ্দীপকের উপরে দেখান (figureOnTop)</span>
                            </label>
                            <div id="cq-remove-figure-wrap" class="hidden">
                                <label class="inline-flex cursor-pointer items-center gap-1.5 text-xs font-bold text-rose-600">
                                    <input id="cq-remove-figure" type="checkbox" name="remove_figure" value="1" class="rounded border-rose-300 text-rose-600">
                                    <span>বর্তমান উদ্দীপক চিত্রটি মুছে ফেলুন</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Sub-Questions with Separate Marks, Optional Figures & Remove Figure -->
                <div class="space-y-3">
                    @foreach (['a' => ['label' => 'ক (জ্ঞানমূলক)', 'default_mark' => 1], 'b' => ['label' => 'খ (অনুধাবনমূলক)', 'default_mark' => 2], 'c' => ['label' => 'গ (প্রয়োগমূলক)', 'default_mark' => 3], 'd' => ['label' => 'ঘ (উচ্চতর দক্ষতা)', 'default_mark' => 4]] as $subKey => $meta)
                        <div class="rounded-xl border border-slate-200 p-3.5">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-5">
                                <div class="sm:col-span-4">
                                    <label class="block text-xs font-bold text-slate-800">প্রশ্ন {{ $meta['label'] }} *</label>
                                    <input
                                        id="cq-question-{{ $subKey }}"
                                        type="text"
                                        name="question_{{ $subKey }}"
                                        required
                                        placeholder="প্রশ্ন {{ $meta['label'] }} লিখুন..."
                                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800">নম্বর *</label>
                                    <input
                                        id="cq-mark-{{ $subKey }}"
                                        type="number"
                                        name="mark_{{ $subKey }}"
                                        value="{{ $meta['default_mark'] }}"
                                        min="1"
                                        required
                                        oninput="recalculateFullMark()"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-bold"
                                    >
                                </div>
                            </div>

                            <div class="mt-2.5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-2">
                                <input type="file" name="figure_{{ $subKey }}_file" accept="image/*" class="text-[11px] text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-slate-200 file:px-2 file:py-1 file:text-[11px] file:font-semibold file:text-slate-700">
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex cursor-pointer items-center gap-1.5 text-[11px] font-medium text-slate-600">
                                        <input id="cq-figure-{{ $subKey }}-top" type="checkbox" name="figure_{{ $subKey }}_on_top" value="1" class="rounded border-slate-300 text-indigo-600">
                                        <span>প্রশ্নের উপরে চিত্র (figureOnTop)</span>
                                    </label>
                                    <span id="cq-remove-figure-{{ $subKey }}-wrap" class="hidden">
                                        <label class="inline-flex cursor-pointer items-center gap-1 text-[11px] font-bold text-rose-600">
                                            <input id="cq-remove-figure-{{ $subKey }}" type="checkbox" name="remove_figure_{{ $subKey }}" value="1" class="rounded border-rose-300 text-rose-600">
                                            <span>চিত্র মুছুন</span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">উত্তর ও ব্যাখ্যা (Answer Explanation)</label>
                    <textarea
                        id="cq-explanation"
                        name="answer_explanation"
                        rows="3"
                        placeholder="ক, খ, গ ও ঘ নং প্রশ্নের উত্তর বা সমাধান নির্দেশিকা লিখুন..."
                        class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeCreativeModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="cursor-pointer rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                        সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div
        id="delete-creative-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeDeleteCreativeModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 id="delete-creative-heading" class="text-lg font-bold text-slate-900">সৃজনশীল প্রশ্নটি মুছে ফেলতে চান?</h3>
                    <p class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-creative-label" class="font-bold text-slate-900"></span> মুছে ফেলতে যাচ্ছেন।
                    </p>
                </div>
            </div>

            <form id="delete-creative-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteCreativeModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    না, বাতিল করুন
                </button>
                <button type="submit" class="cursor-pointer rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                    হ্যাঁ, মুছে ফেলুন
                </button>
            </form>
        </div>
    </div>

    <script>
        const figureDestroyBaseUrl = @js(url('/admin/questions/creative'));

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

        function recalculateFullMark() {
            const a = parseInt(document.getElementById('cq-mark-a').value || '0', 10);
            const b = parseInt(document.getElementById('cq-mark-b').value || '0', 10);
            const c = parseInt(document.getElementById('cq-mark-c').value || '0', 10);
            const d = parseInt(document.getElementById('cq-mark-d').value || '0', 10);
            document.getElementById('cq-total-mark-display').textContent = a + b + c + d;
        }

        function openViewCreativeModal(q) {
            document.getElementById('view-cq-class').textContent = q.academic_class ? q.academic_class.name : '';
            document.getElementById('view-cq-subject').textContent = q.subject ? q.subject.name : '';
            document.getElementById('view-cq-chapter').textContent = q.chapter ? q.chapter.name : '';
            document.getElementById('view-cq-fullmark').textContent = 'পূর্ণমান: ' + q.full_mark;
            document.getElementById('view-cq-title').textContent = q.title;

            const topWrap = document.getElementById('view-cq-stem-fig-top');
            const bottomWrap = document.getElementById('view-cq-stem-fig-bottom');
            topWrap.classList.add('hidden');
            bottomWrap.classList.add('hidden');

            if (q.figure) {
                const delUrl = `${figureDestroyBaseUrl}/${q.id}/figure/figure`;
                if (q.figure_on_top) {
                    document.getElementById('view-cq-stem-img-top').src = q.figure;
                    document.getElementById('view-cq-del-stem-fig-top').onclick = () => openDeleteFigureModal(delUrl);
                    topWrap.classList.remove('hidden');
                } else {
                    document.getElementById('view-cq-stem-img-bottom').src = q.figure;
                    document.getElementById('view-cq-del-stem-fig-bottom').onclick = () => openDeleteFigureModal(delUrl);
                    bottomWrap.classList.remove('hidden');
                }
            }

            const subContainer = document.getElementById('view-cq-subquestions');
            subContainer.innerHTML = '';

            const subList = [
                { key: 'a', label: 'ক', text: q.question_a, mark: q.mark_a, fig: q.figure_a, top: q.figure_a_on_top, field: 'figure_a' },
                { key: 'b', label: 'খ', text: q.question_b, mark: q.mark_b, fig: q.figure_b, top: q.figure_b_on_top, field: 'figure_b' },
                { key: 'c', label: 'গ', text: q.question_c, mark: q.mark_c, fig: q.figure_c, top: q.figure_c_on_top, field: 'figure_c' },
                { key: 'd', label: 'ঘ', text: q.question_d, mark: q.mark_d, fig: q.figure_d, top: q.figure_d_on_top, field: 'figure_d' },
            ];

            subList.forEach((sub) => {
                const div = document.createElement('div');
                div.className = 'rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs space-y-2';

                const figDeleteUrl = `${figureDestroyBaseUrl}/${q.id}/figure/${sub.field}`;
                let html = '';

                if (sub.fig && sub.top) {
                    html += `<div class="flex items-start justify-between gap-2 rounded-lg bg-slate-50 p-2">
                        <img src="${sub.fig}" class="max-h-32 rounded object-contain" alt="চিত্র">
                        <button type="button" onclick="openDeleteFigureModal('${figDeleteUrl}')" class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-600 hover:bg-rose-100">চিত্র মুছুন ✕</button>
                    </div>`;
                }

                html += `<div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-bold text-slate-800"><span class="text-indigo-600">(${sub.label})</span> ${sub.text}</p>
                    <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-extrabold text-slate-800">${sub.mark} নম্বর</span>
                </div>`;

                if (sub.fig && !sub.top) {
                    html += `<div class="flex items-start justify-between gap-2 rounded-lg bg-slate-50 p-2">
                        <img src="${sub.fig}" class="max-h-32 rounded object-contain" alt="চিত্র">
                        <button type="button" onclick="openDeleteFigureModal('${figDeleteUrl}')" class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-600 hover:bg-rose-100">চিত্র মুছুন ✕</button>
                    </div>`;
                }

                div.innerHTML = html;
                subContainer.appendChild(div);
            });

            const expWrap = document.getElementById('view-cq-explanation-wrap');
            if (q.answer_explanation) {
                document.getElementById('view-cq-explanation').textContent = q.answer_explanation;
                expWrap.classList.remove('hidden');
            } else {
                expWrap.classList.add('hidden');
            }

            const modal = document.getElementById('view-creative-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeViewCreativeModal() {
            const modal = document.getElementById('view-creative-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openCreativeModal() {
            document.getElementById('creative-modal-title').textContent = 'নতুন সৃজনশীল প্রশ্ন যোগ করুন';
            document.getElementById('creative-question-form').action = @js(route('admin.questions.creative.store'));
            document.getElementById('creative-form-method').value = 'POST';
            document.getElementById('cq-class').value = '';
            document.getElementById('cq-subject').value = '';
            document.getElementById('cq-chapter').value = '';
            document.getElementById('cq-title').value = '';
            document.getElementById('cq-figure-on-top').checked = false;
            document.getElementById('cq-remove-figure').checked = false;
            document.getElementById('cq-remove-figure-wrap').classList.add('hidden');

            const defaults = { a: 1, b: 2, c: 3, d: 4 };
            ['a', 'b', 'c', 'd'].forEach((k) => {
                document.getElementById('cq-question-' + k).value = '';
                document.getElementById('cq-mark-' + k).value = defaults[k];
                document.getElementById('cq-figure-' + k + '-top').checked = false;
                document.getElementById('cq-remove-figure-' + k).checked = false;
                document.getElementById('cq-remove-figure-' + k + '-wrap').classList.add('hidden');
            });
            document.getElementById('cq-explanation').value = '';
            recalculateFullMark();

            const modal = document.getElementById('creative-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditCreativeModal(q, updateUrl) {
            document.getElementById('creative-modal-title').textContent = 'সৃজনশীল প্রশ্ন সম্পাদনা করুন';
            document.getElementById('creative-question-form').action = updateUrl;
            document.getElementById('creative-form-method').value = 'PUT';
            document.getElementById('cq-class').value = q.class_id;
            cascadeSubjects('cq-class', 'cq-subject', 'cq-chapter', q.subject_id, q.chapter_id);
            document.getElementById('cq-title').value = q.title;
            document.getElementById('cq-figure-on-top').checked = Boolean(q.figure_on_top);
            document.getElementById('cq-remove-figure').checked = false;
            document.getElementById('cq-remove-figure-wrap').classList.toggle('hidden', !q.figure);

            ['a', 'b', 'c', 'd'].forEach((k) => {
                document.getElementById('cq-question-' + k).value = q['question_' + k] || '';
                document.getElementById('cq-mark-' + k).value = q['mark_' + k] || 1;
                document.getElementById('cq-figure-' + k + '-top').checked = Boolean(q['figure_' + k + '_on_top']);
                document.getElementById('cq-remove-figure-' + k).checked = false;
                document.getElementById('cq-remove-figure-' + k + '-wrap').classList.toggle('hidden', !q['figure_' + k]);
            });
            document.getElementById('cq-explanation').value = q.answer_explanation || '';
            recalculateFullMark();

            const modal = document.getElementById('creative-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCreativeModal() {
            const modal = document.getElementById('creative-question-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteCreativeModal(title, deleteUrl) {
            document.getElementById('delete-creative-heading').textContent = 'সৃজনশীল প্রশ্নটি মুছে ফেলতে চান?';
            document.getElementById('delete-creative-form').action = deleteUrl;
            document.getElementById('delete-creative-label').textContent = '“' + title + '” উদ্দীপকের প্রশ্নটি';
            const modal = document.getElementById('delete-creative-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openDeleteFigureModal(deleteFigureUrl) {
            closeViewCreativeModal();
            document.getElementById('delete-creative-heading').textContent = 'চিত্রটি মুছে ফেলতে চান?';
            document.getElementById('delete-creative-form').action = deleteFigureUrl;
            document.getElementById('delete-creative-label').textContent = 'এই সংযুক্ত চিত্রটি';
            const modal = document.getElementById('delete-creative-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteCreativeModal() {
            const modal = document.getElementById('delete-creative-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-layouts.admin>
