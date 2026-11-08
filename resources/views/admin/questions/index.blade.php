<x-layouts.admin title="প্রশ্নভাণ্ডার (Questions)" heading="প্রশ্নভাণ্ডার ও প্রশ্নপত্র">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-2xs">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">প্রশ্নভাণ্ডার তালিকা</h2>
                <p class="text-xs text-slate-500">সৃজনশীল (CQ), বহুনির্বাচনি (MCQ) ও সংক্ষিপ্ত প্রশ্ন পরিচালনা করুন।</p>
            </div>
            <button
                type="button"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-2xs hover:bg-indigo-500"
            >
                + নতুন প্রশ্ন যুক্ত করুন
            </button>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($questions as $question)
                <div class="flex flex-col justify-between gap-4 p-6 hover:bg-slate-50/70 sm:flex-row sm:items-center">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                                {{ $question['class_name'] }}
                            </span>
                            <span class="rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-semibold text-violet-700">
                                {{ $question['subject_name'] }}
                            </span>
                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                                {{ $question['type'] }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">
                            প্রশ্ন #{{ $question['id'] }}: {{ $question['title'] }}
                        </h3>
                    </div>

                    <div class="flex shrink-0 items-center gap-4 text-xs font-semibold">
                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-slate-700">
                            মান: {{ $question['marks'] }}
                        </span>
                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-slate-700">
                            স্তর: {{ $question['difficulty'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.admin>
