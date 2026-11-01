{{-- Pill for a tool status; always carries the text so colour is never the only signal --}}
@props(['status'])

@php
    $colors = match ($status) {
        \App\Enums\ToolStatus::Pending => 'bg-warning-soft text-warning-deep',
        \App\Enums\ToolStatus::Success => 'bg-link-soft text-link-deep',
        \App\Enums\ToolStatus::Failed => 'bg-error-soft text-error-deep',
    };
@endphp

<span {{ $attributes->class(['inline-block rounded-full px-2 text-xs font-medium', $colors]) }}>{{ $status->value }}</span>
