@props(['item'])

<div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
    <span class="text-gray-500">{{ $item->position }}.</span>
    <div class="flex-grow">
        <div class="font-medium text-gray-900">{{ $item->title }}</div>
        @if($item->description)
            <div class="text-sm text-gray-500">{{ $item->description }}</div>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex space-x-2">
            {{ $actions }}
        </div>
    @endif
</div>