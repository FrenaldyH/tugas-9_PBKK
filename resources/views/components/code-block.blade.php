{{-- Dark JSON block with a mono caption --}}
@props(['label', 'data'])

<div>
    <p class="mb-1 font-mono text-xs uppercase text-mute">{{ $label }}</p>
    <pre class="overflow-x-auto rounded-lg bg-ink p-4 font-mono text-[13px] leading-5 text-white">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
</div>
