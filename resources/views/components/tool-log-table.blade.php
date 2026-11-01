{{-- Tool calls of one assistant message: TOOL | AGENT | STATUS | DURASI --}}
@props(['logs'])

{{-- overflow-x-auto keeps a wide table from scrolling the whole page on small screens --}}
<div class="mt-3 overflow-x-auto rounded-lg border border-hairline bg-canvas">
    <table class="w-full text-left text-sm">
        <thead class="bg-canvas-soft font-mono text-xs uppercase text-mute">
            <tr>
                <th class="px-3 py-2 font-normal">Tool</th>
                <th class="px-3 py-2 font-normal">Agent</th>
                <th class="px-3 py-2 font-normal">Status</th>
                <th class="px-3 py-2 font-normal">Durasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr class="border-t border-hairline align-top">
                    <td class="px-3 py-2">
                        {{-- <details> opens the JSON without any JavaScript --}}
                        <details>
                            <summary class="cursor-pointer font-mono">{{ $log->tool_name }}</summary>
                            <div class="mt-2 space-y-2">
                                @if ($log->input)
                                    <x-code-block label="input" :data="$log->input" />
                                @endif
                                @if ($log->result)
                                    <x-code-block label="result" :data="$log->result" />
                                @endif
                                @if ($log->error_message)
                                    <p class="text-error-deep">{{ $log->error_message }}</p>
                                @endif
                            </div>
                        </details>
                    </td>
                    <td class="px-3 py-2">
                        {{-- loggable_type is the morph alias; loggable is already eager loaded.
                             It can be null when an agent was bulk-deleted, which skips model events --}}
                        <span class="inline-block rounded-full bg-canvas-soft px-2 font-mono text-xs text-body">{{ $log->loggable_type }}</span>
                        <span class="text-mute">{{ $log->loggable?->name ?? '(agent sudah dihapus)' }}</span>
                    </td>
                    <td class="px-3 py-2"><x-status-badge :status="$log->status" /></td>
                    <td class="whitespace-nowrap px-3 py-2 font-mono text-xs text-body">
                        {{ $log->duration_ms !== null ? $log->duration_ms.' ms' : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
