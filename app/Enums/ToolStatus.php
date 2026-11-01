<?php

namespace App\Enums;

// Lifecycle state of one tool call
enum ToolStatus: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';
}
