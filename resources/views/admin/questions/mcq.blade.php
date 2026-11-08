<x-layouts.admin title="বহুনির্বাচনি প্রশ্ন (MCQ)" heading="বহুনির্বাচনি প্রশ্ন (MCQ) ব্যবস্থাপনা">
    <div class="space-y-6">
        <!-- Top Header & Search/Filter Bar -->
        <div class="rounded-2xl border border-violet-100 bg-gradient-to-br from-white via-white to-violet-50/40 p-6 shadow-xs">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-md shadow-violet-600/25">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">বহুনির্বাচনি প্রশ্নভাণ্ডার (MCQ Questions)</h2>
                        <p class="mt-0.5 text-xs text-slate-500">মূল প্রশ্ন (ডিফল্ট নম্বর: ১), ৪টি অপশন, সঠিক উত্তর, সংক্ষিপ্ত ব্যাখ্যা এবং প্রশ্ন ও অপশনের চিত্র পরিচালনা করুন।</p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openMcqModal()"
                    class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-violet-600/20 transition hover:bg-violet-500"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>নতুন MCQ প্রশ্ন</span>
                </button>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.questions.mcq.index') }}" class="mt-5 grid grid-cols-1 gap-3 border-t border-slate-100 pt-5 sm:grid-cols-12">
                <div class="sm:col-span-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="প্রশ্ন, অপশন বা ব্যাখ্যা লিখে খুঁজুন..."
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-violet-500 focus:outline-none"
                    >
                </div>

                <div class="sm:col-span-2">
                    <select
                        name="class_id"
                        id="filter-mcq-class"
                        onchange="cascadeSubjects('filter-mcq-class', 'filter-mcq-subject', 'filter-mcq-chapter')"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-violet-500 focus:outline-none"
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
                        id="filter-mcq-subject"
                        onchange="cascadeChapters('filter-mcq-subject', 'filter-mcq-chapter')"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-violet-500 focus:outline-none"
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
                        id="filter-mcq-chapter"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-violet-500 focus:outline-none"
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
                    <a href="{{ route('admin.questions.mcq.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        রিসেট
                    </a>
                </div>
            </form>
        </div>

        <!-- MCQ Questions Table Card -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-slate-900">বহুনির্বাচনি প্রশ্ন তালিকা</h3>
                    <span class="inline-flex items-center rounded-full bg-violet-50 px-3 py-0.5 text-xs font-bold text-violet-700 ring-1 ring-violet-600/15">
                        মোট {{ $questions->total() }} টি
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 bg-slate-50/90 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            <th class="w-20 px-6 py-4">ক্রমিক</th>
                            <th class="px-6 py-4">প্রশ্ন, অপশনসমূহ ও ব্যাখ্যা</th>
                            <th class="w-60 px-6 py-4">শ্রেণি / বিষয় / অধ্যায়</th>
                            <th class="w-24 px-6 py-4 text-center">নম্বর</th>
                            <th class="w-32 px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($questions as $index => $q)
                            <tr class="group transition-colors duration-150 hover:bg-violet-50/25">
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 group-hover:bg-violet-600 group-hover:text-white">
                                        {{ str_pad((string) ($questions->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <!-- Main Question Figure BEFORE title if figure_on_top -->
                                    @if ($q->figure && $q->figure_on_top)
                                        <div class="mb-2.5 inline-block rounded-xl border border-slate-200 bg-slate-50 p-2">
                                            <div class="mb-1 flex items-center justify-between gap-3">
                                                <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">প্রশ্ন চিত্র (উপরে)</span>
                                                <button
                                                    type="button"
                                                    onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'mcq', 'id' => $q->id, 'field' => 'figure'])))"
                                                    class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                >
                                                    চিত্র মুছুন ✕
                                                </button>
                                            </div>
                                            <img src="{{ $q->figure }}" alt="প্রশ্ন চিত্র" class="max-h-36 rounded-lg object-contain">
                                        </div>
                                    @endif

                                    <p class="text-base font-bold text-slate-900">{{ $q->title }}</p>

                                    <!-- Main Question Figure AFTER title if !figure_on_top -->
                                    @if ($q->figure && ! $q->figure_on_top)
                                        <div class="mt-2.5 inline-block rounded-xl border border-slate-200 bg-slate-50 p-2">
                                            <div class="mb-1 flex items-center justify-between gap-3">
                                                <span class="rounded bg-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-700">প্রশ্ন চিত্র (নিচে)</span>
                                                <button
                                                    type="button"
                                                    onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'mcq', 'id' => $q->id, 'field' => 'figure'])))"
                                                    class="cursor-pointer rounded bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                >
                                                    চিত্র মুছুন ✕
                                                </button>
                                            </div>
                                            <img src="{{ $q->figure }}" alt="প্রশ্ন চিত্র" class="max-h-36 rounded-lg object-contain">
                                        </div>
                                    @endif

                                    <!-- 4 Options Grid -->
                                    @php
                                        $options = [
                                            'a' => ['label' => 'ক', 'text' => $q->option_a, 'fig' => $q->option_a_figure, 'top' => $q->option_a_figure_on_top, 'field' => 'option_a_figure'],
                                            'b' => ['label' => 'খ', 'text' => $q->option_b, 'fig' => $q->option_b_figure, 'top' => $q->option_b_figure_on_top, 'field' => 'option_b_figure'],
                                            'c' => ['label' => 'গ', 'text' => $q->option_c, 'fig' => $q->option_c_figure, 'top' => $q->option_c_figure_on_top, 'field' => 'option_c_figure'],
                                            'd' => ['label' => 'ঘ', 'text' => $q->option_d, 'fig' => $q->option_d_figure, 'top' => $q->option_d_figure_on_top, 'field' => 'option_d_figure'],
                                        ];
                                    @endphp

                                    <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                        @foreach ($options as $key => $opt)
                                            @php $isCorrect = strtolower($q->answer_option) === $key; @endphp
                                            <div class="rounded-xl border px-3 py-2 text-xs {{ $isCorrect ? 'border-emerald-300 bg-emerald-50/80 font-bold text-emerald-950' : 'border-slate-200 bg-slate-50/70 text-slate-700' }}">
                                                @if ($opt['fig'] && $opt['top'])
                                                    <div class="mb-1.5 flex items-start justify-between gap-2">
                                                        <img src="{{ $opt['fig'] }}" alt="অপশন চিত্র" class="max-h-20 rounded border border-slate-200 object-contain">
                                                        <button
                                                            type="button"
                                                            onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'mcq', 'id' => $q->id, 'field' => $opt['field']])))"
                                                            class="cursor-pointer rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                        >
                                                            চিত্র মুছুন ✕
                                                        </button>
                                                    </div>
                                                @endif

                                                <div class="flex items-center justify-between gap-2">
                                                    <span>({{ $opt['label'] }}) {{ $opt['text'] }}</span>
                                                    @if ($isCorrect)
                                                        <span class="rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-bold text-white">সঠিক</span>
                                                    @endif
                                                </div>

                                                @if ($opt['fig'] && ! $opt['top'])
                                                    <div class="mt-1.5 flex items-start justify-between gap-2">
                                                        <img src="{{ $opt['fig'] }}" alt="অপশন চিত্র" class="max-h-20 rounded border border-slate-200 object-contain">
                                                        <button
                                                            type="button"
                                                            onclick="openDeleteFigureModal(@js(route('admin.questions.figure.destroy', ['type' => 'mcq', 'id' => $q->id, 'field' => $opt['field']])))"
                                                            class="cursor-pointer rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-600 hover:bg-rose-100"
                                                        >
                                                            চিত্র মুছুন ✕
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    @if ($q->short_explanation)
                                        <div class="mt-2.5 rounded-xl bg-slate-100/80 px-3.5 py-2 text-xs text-slate-700">
                                            <span class="font-bold text-slate-900">সংক্ষিপ্ত ব্যাখ্যা:</span> {{ $q->short_explanation }}
                                        </div>
                                    @endif
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
                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openEditMcqModal(@js($q), @js(route('admin.questions.mcq.update', $q)))"
                                                aria-label="সম্পাদনা করুন"
                                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <span class="pointer-events-none invisible absolute -top-9 left-1/2 z-20 -translate-x-1/2 rounded-lg bg-slate-900 px-2.5 py-1 text-[11px] font-medium whitespace-nowrap text-white opacity-0 shadow-md transition-all group-hover/tooltip:visible group-hover/tooltip:opacity-100">
                                                সম্পাদনা করুন
                                            </span>
                                        </div>

                                        <div class="group/tooltip relative inline-block">
                                            <button
                                                type="button"
                                                onclick="openDeleteMcqModal(@js($q->title), @js(route('admin.questions.mcq.destroy', $q)))"
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
                                    <p class="text-sm font-semibold text-slate-700">কোনো বহুনির্বাচনি প্রশ্ন (MCQ) পাওয়া যায়নি</p>
                                    <p class="mt-1 text-xs text-slate-500">উপরে ডানপাশে “নতুন MCQ প্রশ্ন” বাটনে ক্লিক করে প্রশ্ন যুক্ত করুন।</p>
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

    <!-- Add / Edit MCQ Modal -->
    <div
        id="mcq-question-modal"
        class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeMcqModal()"
    >
        <div class="my-6 w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 id="mcq-modal-title" class="text-lg font-bold text-slate-900">নতুন বহুনির্বাচনি প্রশ্ন (MCQ) যোগ করুন</h3>
                <button type="button" onclick="closeMcqModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">✕</button>
            </div>

            <form id="mcq-question-form" method="POST" action="{{ route('admin.questions.mcq.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" id="mcq-form-method" name="_method" value="POST">

                <!-- Class, Subject, Chapter -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700">শ্রেণি *</label>
                        <select
                            id="mcq-class"
                            name="class_id"
                            required
                            onchange="cascadeSubjects('mcq-class', 'mcq-subject', 'mcq-chapter')"
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
                            id="mcq-subject"
                            name="subject_id"
                            required
                            onchange="cascadeChapters('mcq-subject', 'mcq-chapter')"
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
                            id="mcq-chapter"
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

                <!-- Main Question Title, Default Mark = 1, & Figure -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700">মূল প্রশ্ন (Question Title) *</label>
                        <input
                            id="mcq-title"
                            type="text"
                            name="title"
                            required
                            placeholder="প্রশ্নটি লিখুন..."
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700">নম্বর (ডিফল্ট ১) *</label>
                        <input
                            id="mcq-mark"
                            type="number"
                            name="mark"
                            value="1"
                            min="1"
                            required
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3.5">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:items-center">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">মূল প্রশ্নের চিত্র (ঐচ্ছিক)</label>
                            <input type="file" name="figure_file" accept="image/*" class="mt-1 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-violet-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-700">
                                <input id="mcq-figure-on-top" type="checkbox" name="figure_on_top" value="1" class="h-4 w-4 rounded border-slate-300 text-violet-600">
                                <span>মূল চিত্রটি প্রশ্নের উপরে দেখান (figureOnTop)</span>
                            </label>
                            <div id="mcq-remove-figure-wrap" class="hidden">
                                <label class="inline-flex cursor-pointer items-center gap-1.5 text-xs font-bold text-rose-600">
                                    <input id="mcq-remove-figure" type="checkbox" name="remove_figure" value="1" class="rounded border-rose-300 text-rose-600">
                                    <span>বর্তমান মূল চিত্রটি মুছে ফেলুন</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Options with optional Figure, figureOnTop & Remove Figure -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach (['a' => 'ক (Option A)', 'b' => 'খ (Option B)', 'c' => 'গ (Option C)', 'd' => 'ঘ (Option D)'] as $optKey => $optLabel)
                        <div class="rounded-xl border border-slate-200 p-3.5">
                            <label class="block text-xs font-bold text-slate-800">অপশন {{ $optLabel }} *</label>
                            <input
                                id="mcq-option-{{ $optKey }}"
                                type="text"
                                name="option_{{ $optKey }}"
                                required
                                placeholder="অপশন {{ $optLabel }} লিখুন..."
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            >
                            <div class="mt-2.5 space-y-1.5 border-t border-slate-100 pt-2">
                                <label class="block text-[11px] font-semibold text-slate-500">অপশন চিত্র (ঐচ্ছিক)</label>
                                <input type="file" name="option_{{ $optKey }}_figure_file" accept="image/*" class="block w-full text-[11px] text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-slate-200 file:px-2 file:py-1 file:text-[11px] file:font-semibold file:text-slate-700">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <label class="inline-flex cursor-pointer items-center gap-1.5 text-[11px] font-medium text-slate-600">
                                        <input id="mcq-option-{{ $optKey }}-top" type="checkbox" name="option_{{ $optKey }}_figure_on_top" value="1" class="rounded border-slate-300 text-violet-600">
                                        <span>উপরে চিত্র (figureOnTop)</span>
                                    </label>
                                    <span id="mcq-remove-option-{{ $optKey }}-wrap" class="hidden">
                                        <label class="inline-flex cursor-pointer items-center gap-1 text-[11px] font-bold text-rose-600">
                                            <input id="mcq-remove-option-{{ $optKey }}" type="checkbox" name="remove_option_{{ $optKey }}_figure" value="1" class="rounded border-rose-300 text-rose-600">
                                            <span>চিত্র মুছুন</span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Correct Answer Option & Short Explanation -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700">সঠিক উত্তর অপশন *</label>
                        <select id="mcq-answer-option" name="answer_option" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-bold">
                            <option value="a">ক (Option A)</option>
                            <option value="b">খ (Option B)</option>
                            <option value="c">গ (Option C)</option>
                            <option value="d">ঘ (Option D)</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700">সংক্ষিপ্ত ব্যাখ্যা (Short Explanation)</label>
                        <input
                            id="mcq-explanation"
                            type="text"
                            name="short_explanation"
                            placeholder="উত্তরের সংক্ষিপ্ত ব্যাখ্যা লিখুন..."
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeMcqModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        বাতিল করুন
                    </button>
                    <button type="submit" class="cursor-pointer rounded-xl bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-violet-500">
                        সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div
        id="delete-mcq-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-xs"
        onclick="closeDeleteMcqModal()"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 id="delete-mcq-heading" class="text-lg font-bold text-slate-900">MCQ প্রশ্নটি মুছে ফেলতে চান?</h3>
                    <p class="mt-1.5 text-sm text-slate-600">
                        আপনি <span id="delete-mcq-label" class="font-bold text-slate-900"></span> মুছে ফেলতে যাচ্ছেন।
                    </p>
                </div>
            </div>

            <form id="delete-mcq-form" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteMcqModal()" class="cursor-pointer rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
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

        function openMcqModal() {
            document.getElementById('mcq-modal-title').textContent = 'নতুন বহুনির্বাচনি প্রশ্ন (MCQ) যোগ করুন';
            document.getElementById('mcq-question-form').action = @js(route('admin.questions.mcq.store'));
            document.getElementById('mcq-form-method').value = 'POST';
            document.getElementById('mcq-class').value = '';
            document.getElementById('mcq-subject').value = '';
            document.getElementById('mcq-chapter').value = '';
            document.getElementById('mcq-title').value = '';
            document.getElementById('mcq-mark').value = '1';
            document.getElementById('mcq-figure-on-top').checked = false;
            document.getElementById('mcq-remove-figure').checked = false;
            document.getElementById('mcq-remove-figure-wrap').classList.add('hidden');

            ['a', 'b', 'c', 'd'].forEach((k) => {
                document.getElementById('mcq-option-' + k).value = '';
                document.getElementById('mcq-option-' + k + '-top').checked = false;
                document.getElementById('mcq-remove-option-' + k).checked = false;
                document.getElementById('mcq-remove-option-' + k + '-wrap').classList.add('hidden');
            });
            document.getElementById('mcq-answer-option').value = 'a';
            document.getElementById('mcq-explanation').value = '';

            const modal = document.getElementById('mcq-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditMcqModal(q, updateUrl) {
            document.getElementById('mcq-modal-title').textContent = 'বহুনির্বাচনি প্রশ্ন (MCQ) সম্পাদনা করুন';
            document.getElementById('mcq-question-form').action = updateUrl;
            document.getElementById('mcq-form-method').value = 'PUT';
            document.getElementById('mcq-class').value = q.class_id;
            cascadeSubjects('mcq-class', 'mcq-subject', 'mcq-chapter', q.subject_id, q.chapter_id);
            document.getElementById('mcq-title').value = q.title;
            document.getElementById('mcq-mark').value = q.mark ?? 1;
            document.getElementById('mcq-figure-on-top').checked = Boolean(q.figure_on_top);
            document.getElementById('mcq-remove-figure').checked = false;
            document.getElementById('mcq-remove-figure-wrap').classList.toggle('hidden', !q.figure);

            ['a', 'b', 'c', 'd'].forEach((k) => {
                document.getElementById('mcq-option-' + k).value = q['option_' + k] || '';
                document.getElementById('mcq-option-' + k + '-top').checked = Boolean(q['option_' + k + '_figure_on_top']);
                document.getElementById('mcq-remove-option-' + k).checked = false;
                document.getElementById('mcq-remove-option-' + k + '-wrap').classList.toggle('hidden', !q['option_' + k + '_figure']);
            });
            document.getElementById('mcq-answer-option').value = q.answer_option;
            document.getElementById('mcq-explanation').value = q.short_explanation || '';

            const modal = document.getElementById('mcq-question-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeMcqModal() {
            const modal = document.getElementById('mcq-question-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteMcqModal(title, deleteUrl) {
            document.getElementById('delete-mcq-heading').textContent = 'MCQ প্রশ্নটি মুছে ফেলতে চান?';
            document.getElementById('delete-mcq-form').action = deleteUrl;
            document.getElementById('delete-mcq-label').textContent = '“' + title + '” প্রশ্নটি';
            const modal = document.getElementById('delete-mcq-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openDeleteFigureModal(deleteFigureUrl) {
            document.getElementById('delete-mcq-heading').textContent = 'চিত্রটি মুছে ফেলতে চান?';
            document.getElementById('delete-mcq-form').action = deleteFigureUrl;
            document.getElementById('delete-mcq-label').textContent = 'এই সংযুক্ত চিত্রটি';
            const modal = document.getElementById('delete-mcq-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteMcqModal() {
            const modal = document.getElementById('delete-mcq-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-layouts.admin>
