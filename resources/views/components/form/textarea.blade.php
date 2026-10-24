@props(['name', 'value' => null, 'rows' => 4])

<textarea name="{{ $name }}"
          id="{{ $name }}"
          rows="{{ $rows }}"
          @error($name) aria-invalid="true" @enderror
          {{ $attributes->class(['field-input', 'field-input-error' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
