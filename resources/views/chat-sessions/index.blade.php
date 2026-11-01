<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-ink">Sesi chat</h1>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-3 px-4 py-8 sm:px-6 lg:px-8">
        @forelse ($chatSessions as $chatSession)
            <a href="{{ route('chat-sessions.show', $chatSession) }}"
               class="block rounded-lg border border-hairline bg-canvas p-4 hover:bg-canvas-soft focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">
                <span class="block font-medium">{{ $chatSession->title ?? 'Tanpa judul' }}</span>
                <span class="mt-1 block font-mono text-xs text-mute">
                    {{ $chatSession->messages_count }} pesan · {{ $chatSession->updated_at->diffForHumans() }}
                </span>
            </a>
        @empty
            <p class="text-body">Belum ada sesi.</p>
        @endforelse
    </div>
</x-app-layout>
