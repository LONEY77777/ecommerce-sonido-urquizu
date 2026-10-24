@props(['name', 'type' => 'text', 'value' => null])

<input type="{{ $type }}"
       name="{{ $name }}"
       id="{{ $name }}"
       @if ($type !== 'file') value="{{ old($name, $value) }}" @endif
       @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
       {{ $attributes->class(['field-input', 'field-input-error' => $errors->has($name)]) }}>
