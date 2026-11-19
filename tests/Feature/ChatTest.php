<?php

use App\Ai\Agents\Assistant;
use App\Models\User;
use Illuminate\Support\Str;

test('guests go to the login page', function () {
    $this->get('/chat')->assertRedirect('/login');
});

test('the chat page renders', function () {
    $this->actingAs(User::factory()->create())
        ->get('/chat')
        ->assertInertia(fn ($page) => $page->component('chat'));
});

test('an answer streams back', function () {
    Assistant::fake(['Habari! How can I help?']);

    $response = $this->actingAs(User::factory()->create())
        ->post('/chat/stream', ['id' => (string) Str::uuid7(), 'messages' => [[
            'id' => 'msg_1',
            'role' => 'user',
            'parts' => [['type' => 'text', 'text' => 'Hello!']],
        ]]]);

    // The answer arrives in pieces: one "text-delta" event per word.
    $response->assertOk();
    expect($response->streamedContent())
        ->toContain('"type":"text-delta"')
        ->toContain('"delta":"Habari!"')
        ->toContain('"delta":" help?"');

    Assistant::assertPrompted('Hello!');
});
