<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tool_execution_logs', function (Blueprint $table) {
            $table->id();
            // Deleting a message removes the tool calls it triggered. The explicit index matters on
            // SQLite/PostgreSQL, which (unlike MySQL) do not index foreign key columns on their own
            $table->foreignId('chat_message_id')->index()->constrained()->cascadeOnDelete();
            // Challenge 1: the agent that ran the tool (loggable_type + loggable_id, no DB-level FK)
            $table->morphs('loggable');
            $table->string('tool_name', 100);
            $table->json('input')->nullable();
            $table->json('result')->nullable();
            // pending / success / failed (see App\Enums\ToolStatus)
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('duration_ms')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_execution_logs');
    }
};
