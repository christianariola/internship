@props(['id', 'name', 'label' => null, 'placeholder' => '', 'value' => '', 'cols' => '', 'rows' => ''])
<div class="mb-4">
    @if($label)
        <label class="block text-gray-700" for="description">
            {{ $label }}
        </label>
    @endif
    <textarea
        cols="{{ $cols }}"
        rows="{{ $rows }}"
        id="{{ $id }}"
        name="{{ $name }}"
        class="w-full px-4 py-2 border rounded focus:outline-none @error($name) border-red-500 @enderror"
        placeholder="{{ $placeholder }}"
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>