<?php

namespace App\Models\Concerns;

use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

// Shared by every agent model that can be the "loggable" of a tool log
trait HasToolExecutionLogs
{
    /**
     * Logs written by this agent, via the loggable_type/loggable_id pair.
     */
    public function toolExecutionLogs(): MorphMany
    {
        return $this->morphMany(ToolExecutionLog::class, 'loggable');
    }

    /**
     * The database cannot cascade on a morph column, so clean up here.
     */
    protected static function bootHasToolExecutionLogs(): void
    {
        static::deleting(fn ($agent) => $agent->toolExecutionLogs()->delete());
    }
}
