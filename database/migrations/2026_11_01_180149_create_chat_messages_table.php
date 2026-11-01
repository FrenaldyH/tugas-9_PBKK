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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Deleting a session removes all of its messages
            $table->foreignId('chat_session_id')->constrained()->cascadeOnDelete();
            // user / assistant / system / tool (see App\Enums\MessageRole)
            $table->string('role', 20);
            $table->longText('content');
            $table->timestamps();

            // Message order within a session
            $table->index(['chat_session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
