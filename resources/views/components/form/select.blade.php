@props(['name', 'value' => null, 'options' => [], 'placeholder' => null])

<select name="{{ $name }}"
        id="{{ $name }}"
        @error($name) aria-invalid="true" @enderror
        {{ $attributes->class(['field-input', 'field-input-error' => $errors->has($name)]) }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>
            {{ $optionLabel }}
        </option>
    @endforeach

    {{ $slot }}
</select>
