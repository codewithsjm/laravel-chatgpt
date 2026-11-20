<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

#[Timeout(30)]
class Assistant implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(public bool $smart = false) {}

    /**
     * Fast or smart model first. Busy, or out of free requests? The backup answers.
     */
    public function provider(): array
    {
        return [
            'gemini' => $this->smart ? config('ai.providers.gemini.models.text.smartest') : null,
            'gemini-backup' => null,
        ];
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $rules = <<<'TEXT'
        You are Swali, a friendly and clear assistant.
        Give direct answers first, then details if they help.
        Use Markdown: short paragraphs, lists, and code blocks with a language.
        TEXT;

        // The user's own instructions, from Settings > Personalization.
        $about = $this->conversationParticipant()?->instructions;

        return $about ? $rules."\n\nWhat the user told you about themselves:\n".$about : $rules;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return list<Agent|Tool|ProviderTool>
     */
    public function tools(): iterable
    {
        return [];
    }
}
