<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    // The root URL has no page of its own; it forwards to the chat session list
    public function test_the_root_url_redirects_to_chat_sessions(): void
    {
        $this->get('/')->assertRedirect('/chat-sessions');
    }
}
