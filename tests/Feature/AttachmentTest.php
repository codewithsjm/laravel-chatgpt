<?php

use App\Ai\Agents\Assistant;
use App\Ai\Agents\TitleWriter;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Ai\Prompts\AgentPrompt;

beforeEach(function () {
    Assistant::fake(['That is a receipt from a duka in Arusha.']);
    TitleWriter::fake(['A receipt']);
});

function withFile(string $mediaType, string $filename): array
{
    return ['id' => (string) Str::uuid7(), 'messages' => [[
        'id' => 'msg_1',
        'role' => 'user',
        'parts' => [
            ['type' => 'file', 'mediaType' => $mediaType, 'filename' => $filename,
                'url' => "data:{$mediaType};base64,".base64_encode('fake file')],
            ['type' => 'text', 'text' => 'What is this?'],
        ],
    ]]];
}

test('an image goes to the assistant with the question', function () {
    $this->actingAs(User::factory()->create())
        ->post('/chat/stream', withFile('image/png', 'receipt.png'))
        ->assertOk()
        ->streamedContent();

    Assistant::assertPrompted(fn (AgentPrompt $prompt) => $prompt->attachments->count() === 1);
});

test('other file types are refused', function () {
    $this->actingAs(User::factory()->create())
        ->postJson('/chat/stream', withFile('application/x-msdownload', 'virus.exe'))
        ->assertUnprocessable();

    Assistant::assertNeverPrompted();
});
