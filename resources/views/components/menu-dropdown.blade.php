<div class="relative" x-data="{ open: false }" x-cloak @click.away="open = false" @keydown.escape.window="open = false">
    <button type="button" @click="open = !open" class="flex items-center h-full">
        {{ $slot }}
    </button>

    <div
        x-show.transition="open"
        class="transform origin-top-right absolute {{ $attributes['align'] }}-0 mt-2 rounded-md shadow-lg z-10"
    >
        {{ $dropdown }}
    </div>
</div>
