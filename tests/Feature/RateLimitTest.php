<?php

use App\Ai\Agents\Assistant;
use App\Ai\Agents\TitleWriter;
use App\Models\User;
use Illuminate\Support\Str;

test('ten messages a minute, then a polite no', function () {
    Assistant::fake(fn () => 'Sawa!');
    TitleWriter::fake(fn () => 'A chat');
    $this->actingAs(User::factory()->create());

    $message = fn () => ['id' => (string) Str::uuid7(), 'messages' => [[
        'id' => 'msg_'.Str::random(6),
        'role' => 'user',
        'parts' => [['type' => 'text', 'text' => 'Hello']],
    ]]];

    foreach (range(1, 10) as $i) {
        $this->post('/chat/stream', $message())->assertOk()->streamedContent();
    }

    $this->post('/chat/stream', $message())
        ->assertTooManyRequests()
        ->assertJson(['message' => 'You have sent a lot of messages. Take a short break, then try again.']);
});
