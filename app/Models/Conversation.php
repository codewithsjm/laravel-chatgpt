<?php

namespace App\Models;

use Laravel\Ai\Models\Conversation as AiConversation;

// The AI SDK already stores conversations (agent_conversations): we just give them a home in our app.
class Conversation extends AiConversation {}
