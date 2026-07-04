@props([
    'label',
    'name',
    'type' => 'text',
    'as' => 'input',     // input | textarea
    'required' => false,
    'placeholder' => '',
    'rows' => 4,
    'autocomplete' => null,
    'value' => null,
])

@php
    $id = 'field-' . $name;
    $hasError = $errors->has($name);
    $current = old($name, $value);

    $base = 'block w-full rounded-xl border bg-white px-4 py-3 text-slate-900 '
        . 'placeholder:text-slate-400 shadow-sm transition focus:ring-2 focus:outline-none';
    $state = $hasError
        ? 'border-red-300 focus:border-red-500 focus:ring-red-500/30'
        : 'border-slate-200 focus:border-brand-500 focus:ring-brand-500/30';
    $classes = $base . ' ' . $state;
@endphp

<div class="space-y-1.5">
    <label for="{{ $id }}" class="block text-sm font-medium text-slate-700">
        {{ $label }}
        @if ($required) <span class="text-cta-600" aria-hidden="true">*</span> @endif
    </label>

    @if ($as === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
                  placeholder="{{ $placeholder }}" @if($required) required @endif
                  @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                  {{ $attributes->merge(['class' => $classes]) }}>{{ $current }}</textarea>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
               value="{{ $current }}"
               placeholder="{{ $placeholder }}" @if($required) required @endif
               @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
               @if($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->merge(['class' => $classes]) }}>
    @endif

    @error($name)
        <p id="{{ $id }}-error" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
