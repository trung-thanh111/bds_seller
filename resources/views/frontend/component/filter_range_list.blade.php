@php
    $name = $name ?? '';
    $options = $options ?? [];
    $currentValue = request($name);
    $formId = $formId ?? 'filter-form';
    $isBar = $isBar ?? false;
@endphp

<div class="hp-selection-list">
    @foreach ($options as $val => $txt)
        <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
            <span>{{ $txt }}</span>
            <input type="radio" class="{{ $isBar ? 'bar-sync-input' : 'uk-radio' }}" data-name="{{ $name }}"
                name="{{ $name }}" value="{{ $val }}" form="{{ $formId }}"
                {{ $currentValue == $val ? 'checked' : '' }}>
        </label>
    @endforeach
</div>
