@props([
    'options',
    'label' => null,
    'value' => null,
])

<label class="block">
    @if($label)
        <div class="text-gray-700">{{ $label }}</div>
    @endif

    <select {{ $attributes->merge(['class' => 'border rounded cursor-pointer w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) }}>
        @forelse($options as $key => $label)
            <option value="{{ $key }}" @selected($value == $key)>{{ $label }}</option>
        @empty
            <option disabled>No options available</option>
        @endforelse
    </select>
</label>
