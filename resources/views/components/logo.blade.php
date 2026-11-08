@props([
    'dark' => false,
    'size' => 'md',
])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 select-none']) }}>
    <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-md shadow-indigo-500/20">
        <svg class="h-7 w-7" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="23" cy="22" r="12" stroke="#FFFFFF" stroke-width="3.5" />
            <path d="M20.2 18.8C20.2 17 21.6 15.6 23.4 15.6C25.2 15.6 26.6 17 26.6 18.8C26.6 20.3 25.5 21.1 24.4 21.8C23.5 22.3 23.1 22.9 23.1 24" stroke="#FBBF24" stroke-width="2.8" stroke-linecap="round" />
            <circle cx="23.1" cy="27.6" r="1.6" fill="#FBBF24" />
            <path d="M31 30L38.5 37.5" stroke="#FBBF24" stroke-width="4" stroke-linecap="round" />
        </svg>
    </div>

    <div class="flex flex-col leading-tight">
        <span class="text-lg font-extrabold tracking-tight {{ $dark ? 'text-white' : 'text-slate-900' }}">
            Question<span class="{{ $dark ? 'text-amber-400' : 'text-indigo-600' }}">MKR</span>
        </span>
        <span class="text-[11px] font-medium {{ $dark ? 'text-slate-400' : 'text-slate-500' }}">
            প্রশ্ন তৈরি ও ব্যবস্থাপনা
        </span>
    </div>
</div>
