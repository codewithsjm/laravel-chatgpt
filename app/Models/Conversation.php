<?php

namespace App\Models;

use Laravel\Ai\Models\Conversation as AiConversation;

// The AI SDK already stores conversations (agent_conversations): we just give them a home in our app.
class Conversation extends AiConversation
{
    /**
     * Keep the first $count messages, forget the rest.
     */
    public function keepFirst(int $count): void
    {
        $this->messages()->orderBy('id')->get()->slice($count)->each->delete();
    }
}
