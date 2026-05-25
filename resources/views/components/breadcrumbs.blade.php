@props(['items' => []])

<nav class="flex items-center text-sm text-gray-500 mb-2 space-x-2">
    @foreach ($items as $item)
        @if ($loop->last)
            <span class="text-gray-800 font-medium truncate max-w-xs">{{ $item['label'] }}</span>
        @elseif (!empty($item['url']))
            <a href="{{ $item['url'] }}" class="hover:text-indigo-600 transition whitespace-nowrap">{{ $item['label'] }}</a>
            <span class="text-gray-300">/</span>
        @else
            <span class="text-gray-500 whitespace-nowrap">{{ $item['label'] }}</span>
            <span class="text-gray-300">/</span>
        @endif
    @endforeach
</nav>
