<x-layouts.app title="ব্যবহারকারী ড্যাশবোর্ড — QuestionMKR">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">স্বাগতম, {{ auth()->user()->organization_name }}</h1>
                <p class="mt-1 text-sm text-slate-600">আপনি সফলভাবে ব্যবহারকারী ড্যাশবোর্ডে প্রবেশ করেছেন।</p>
            </div>
            <span class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                ভূমিকা: ব্যবহারকারী ({{ auth()->user()->role->value }})
            </span>
        </div>

        <dl class="mt-6 grid grid-cols-1 gap-4 border-t border-slate-100 pt-6 sm:grid-cols-3">
            <div class="rounded-xl bg-slate-50 p-4">
                <dt class="text-xs font-medium tracking-wider text-slate-500 uppercase">প্রতিষ্ঠানের নাম</dt>
                <dd class="mt-1 text-sm font-semibold text-slate-900">{{ auth()->user()->organization_name }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <dt class="text-xs font-medium tracking-wider text-slate-500 uppercase">ইমেইল ঠিকানা</dt>
                <dd class="mt-1 text-sm font-semibold text-slate-900">{{ auth()->user()->email }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <dt class="text-xs font-medium tracking-wider text-slate-500 uppercase">ফোন নম্বর</dt>
                <dd class="mt-1 text-sm font-semibold text-slate-900">{{ auth()->user()->phone_number }}</dd>
            </div>
        </dl>
    </div>
</x-layouts.app>
