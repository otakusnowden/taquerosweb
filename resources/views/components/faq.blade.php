@props([
    'items' => collect(),
])

@php $items = collect($items)->values(); @endphp

@if ($items->count())
<div x-data="{ open: 0 }" class="mx-auto max-w-3xl divide-y divide-slate-200 rounded-3xl border border-slate-200 bg-white shadow-card">
    @foreach ($items as $i => $faq)
        <div class="px-6 sm:px-8">
            <h3>
                <button type="button"
                        x-on:click="open = (open === {{ $i }} ? null : {{ $i }})"
                        class="flex w-full items-center justify-between gap-4 py-5 text-left"
                        :aria-expanded="open === {{ $i }}"
                        aria-controls="faq-panel-{{ $i }}">
                    <span class="text-[1.05rem] font-semibold text-slate-900">{{ $faq->question }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition"
                          :class="open === {{ $i }} ? 'rotate-180 bg-brand-50 text-brand-600' : ''">
                        <x-icon name="chevron-down" class="w-5 h-5" />
                    </span>
                </button>
            </h3>
            <div id="faq-panel-{{ $i }}" x-show="open === {{ $i }}" x-collapse x-cloak>
                <p class="pb-6 pr-12 text-[0.975rem] leading-relaxed text-slate-600">{{ $faq->answer }}</p>
            </div>
        </div>
    @endforeach
</div>
@endif
