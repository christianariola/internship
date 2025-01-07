@props(['type', 'message', 'timeout' => 5000])

@if(session()->has($type))
    <div class="p-4 mb-4 text-sm text-white rounded {{ $type === 'success' ? 'bg-green-500' : 'bg-red-500' }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, {{ $timeout }})">
        {{ $message}}
    </div>
@endif