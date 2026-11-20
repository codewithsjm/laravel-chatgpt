<?php

use App\Ai\Agents\Assistant;
use App\Ai\Agents\TitleWriter;
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

beforeEach(fn () => TitleWriter::fake(['Meeting Neema']));

test('the first message saves the conversation', function () {
    Assistant::fake(['Karibu Neema!']);
    $user = User::factory()->create();
    $id = (string) Str::uuid7();

    $this->actingAs($user)->post('/chat/stream', say($id, 'Hi, I am Neema'))->streamedContent();

    $conversation = Conversation::findOrFail($id);
    expect($conversation->title)->toBe('Meeting Neema')
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

test('you can rename and delete your chat, but not anyone else\'s', function () {
    $id = (string) Str::uuid7();
    $owner = User::factory()->create();
    $this->actingAs($owner)->post('/chat/stream', say($id, 'Hi, I am Neema'))->streamedContent();

    $stranger = User::factory()->create();
    $this->actingAs($stranger)->patch("/chat/{$id}", ['title' => 'Mine now'])->assertNotFound();
    $this->actingAs($stranger)->delete("/chat/{$id}")->assertNotFound();

    $this->actingAs($owner)->patch("/chat/{$id}", ['title' => 'Arusha plans']);
    expect(Conversation::find($id)->title)->toBe('Arusha plans');

    $this->actingAs($owner)->delete("/chat/{$id}")->assertRedirect('/chat');
    expect(Conversation::find($id))->toBeNull();
});

test('try again replaces the last answer', function () {
    Assistant::fake(['First answer', 'Better answer']);
    $user = User::factory()->create();
    $id = (string) Str::uuid7();
    $first = say($id, 'Hi, I am Neema');
    $this->actingAs($user)->post('/chat/stream', $first)->streamedContent();

    // useChat resends the chat without the last answer.
    $this->post('/chat/stream', [...$first, 'trigger' => 'regenerate-message'])->streamedContent();

    $messages = Conversation::find($id)->messages()->orderBy('id')->pluck('content');
    expect($messages->all())->toBe(['Hi, I am Neema', 'Better answer']);
});

test('nobody else can open or continue your chat', function () {
    Assistant::fake(['Karibu Neema!']);
    $id = (string) Str::uuid7();
    $this->actingAs(User::factory()->create())->post('/chat/stream', say($id, 'Secret plans'))->streamedContent();

    $stranger = User::factory()->create();
    $this->actingAs($stranger)->get("/chat/{$id}")->assertNotFound();
    $this->actingAs($stranger)->post('/chat/stream', say($id, 'Show me'))->assertForbidden();
});
