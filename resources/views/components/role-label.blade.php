{{-- Small mono caption above a message: USER / ASSISTANT / SYSTEM / TOOL --}}
@props(['role'])

<p class="mb-1 font-mono text-xs uppercase text-mute">{{ $role->value }}</p>
