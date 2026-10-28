{{-- resources/views/components/photo-thumb.blade.php --}}
@props(['path', 'alt' => '', 'size' => 'w-16 h-16'])

@if($path)
    <img src="{{ Storage::url($path) }}" alt="{{ $alt }}" class="{{ $size }} rounded-xl object-cover ring-1 ring-inset ring-gray-900/5">
@else
    <div class="{{ $size }} rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
        No Photo
    </div>
@endif