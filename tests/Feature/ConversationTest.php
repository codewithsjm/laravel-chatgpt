<?php

use App\Ai\Agents\Assistant;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Str;

function say(string $id, string $text): array
{
    return ['id' => $id, 'messages' => [[
        'id' => 'msg_'.Str::random(8),
        'role' => 'user',
        'parts' => [['type' => 'text', 'text' => $text]],
    ]]];
}

test('the first message saves the conversation', function () {
    Assistant::fake(['Karibu Neema!']);
    $user = User::factory()->create();
    $id = (string) Str::uuid7();

    $this->actingAs($user)->post('/chat/stream', say($id, 'Hi, I am Neema'))->streamedContent();

    $conversation = Conversation::findOrFail($id);
    expect($conversation->title)->toBe('Hi, I am Neema')
        ->and($conversation->participant->is($user))->toBeTrue()
        ->and($conversation->messages()->count())->toBe(2);
});

test('a saved chat opens with its messages', function () {
    Assistant::fake(['Karibu Neema!']);
    $user = User::factory()->create();
    $id = (string) Str::uuid7();
    $this->actingAs($user)->post('/chat/stream', say($id, 'Hi, I am Neema'))->streamedContent();

    $this->get("/chat/{$id}")->assertInertia(fn ($page) => $page
        ->component('chat')
        ->where('id', $id)
        ->has('messages', 2)
    );
});

test('nobody else can open or continue your chat', function () {
    Assistant::fake(['Karibu Neema!']);
    $id = (string) Str::uuid7();
    $this->actingAs(User::factory()->create())->post('/chat/stream', say($id, 'Secret plans'))->streamedContent();

    $stranger = User::factory()->create();
    $this->actingAs($stranger)->get("/chat/{$id}")->assertNotFound();
    $this->actingAs($stranger)->post('/chat/stream', say($id, 'Show me'))->assertForbidden();
});
