<?php

use App\Ai\Agents\Assistant;
use App\Ai\Agents\TitleWriter;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Ai\Prompts\AgentPrompt;

beforeEach(function () {
    Assistant::fake(['Sawa!']);
    TitleWriter::fake(['A chat']);
});

function ask(array $extra = []): array
{
    return [...$extra, 'id' => (string) Str::uuid7(), 'messages' => [[
        'id' => 'msg_1',
        'role' => 'user',
        'parts' => [['type' => 'text', 'text' => 'Hello']],
    ]]];
}

test('your custom instructions reach the assistant', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch('/settings/personalization', ['instructions' => 'I live in Arusha. Keep it short.'])
        ->assertRedirect('/settings/personalization');

    $this->post('/chat/stream', ask())->streamedContent();

    Assistant::assertPrompted(
        fn (AgentPrompt $prompt) => str_contains($prompt->agent->instructions(), 'I live in Arusha.')
    );
});

test('smart mode asks the smart model first', function () {
    $this->actingAs(User::factory()->create())->post('/chat/stream', ask(['smart' => true]))->streamedContent();

    Assistant::assertPrompted(fn (AgentPrompt $prompt) => $prompt->agent->smart === true);
});
