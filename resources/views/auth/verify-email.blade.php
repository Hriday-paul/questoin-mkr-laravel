<x-layouts.app title="ইমেইল যাচাই করুন — QuestionMKR">
    <div class="mx-auto max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900">আপনার ইমেইল যাচাই করুন</h1>
        <p class="mt-2 text-sm text-slate-600">
            নিবন্ধনের জন্য ধন্যবাদ! শুরু করার পূর্বে আপনার ইমেইলে পাঠানো ভেরিফিকেশন লিংকে ক্লিক করে ইমেইল ঠিকানাটি যাচাই করুন। আপনি যদি ইমেইলটি না পেয়ে থাকেন, তবে নিচে ক্লিক করে পুনরায় পাঠাতে পারেন।
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3.5 text-sm font-medium text-emerald-700">
                আপনার ইমেইল ঠিকানায় একটি নতুন ভেরিফিকেশন লিংক পাঠানো হয়েছে।
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    পুনরায় ভেরিফিকেশন ইমেইল পাঠান
                </button>
            </form>

            <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.logout') : route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                    লগআউট
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
