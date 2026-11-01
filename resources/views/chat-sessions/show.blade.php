<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold tracking-tight text-ink">{{ $chatSession->title ?? 'Tanpa judul' }}</h1>
    </x-slot>

    {{-- pb-24 leaves room for the Debugbar that covers the page bottom in dev --}}
    <div class="mx-auto max-w-3xl space-y-4 px-4 py-8 pb-24 sm:px-6 lg:px-8">
        <a href="{{ route('chat-sessions.index') }}" class="text-sm text-body underline">Kembali ke daftar sesi</a>

        @foreach ($chatSession->messages as $message)
            @php
                $style = match ($message->role) {
                    \App\Enums\MessageRole::User => 'rounded-lg bg-canvas-soft p-4',
                    \App\Enums\MessageRole::Assistant => 'border-t border-hairline py-4',
                    default => 'rounded-lg bg-canvas-soft2 px-4 py-3 text-sm text-body',
                };
            @endphp

            <article class="{{ $style }}">
                <x-role-label :role="$message->role" />
                <p class="whitespace-pre-line">{{ $message->content }}</p>

                @if ($message->toolExecutionLogs->isNotEmpty())
                    <x-tool-log-table :logs="$message->toolExecutionLogs" />
                @endif
            </article>
        @endforeach
    </div>
</x-app-layout>
