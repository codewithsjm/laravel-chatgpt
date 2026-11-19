<?php

namespace App\Http\Controllers;

use App\Ai\Agents\Assistant;
use App\Ai\Agents\TitleWriter;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Vercel\Vercel;

class ChatController extends Controller
{
    public function index(): Response
    {
        // A new chat gets its id now; the first message saves it.
        return Inertia::render('chat', [
            'id' => (string) Str::uuid7(),
            'messages' => [],
        ]);
    }

    public function show(Request $request, string $conversation): Response
    {
        // Only your own conversations: anything else is a 404.
        $conversation = $request->user()->conversations()->findOrFail($conversation);

        return Inertia::render('chat', [
            'id' => $conversation->id,
            'messages' => Vercel::toUiMessages(
                $conversation->messages()->orderBy('id')->get()
            ),
        ]);
    }

    public function update(Request $request, string $conversation): RedirectResponse
    {
        $request->user()->conversations()->findOrFail($conversation)->update(
            $request->validate(['title' => ['required', 'string', 'max:100']])
        );

        return back();
    }

    public function destroy(Request $request, string $conversation): RedirectResponse
    {
        $conversation = $request->user()->conversations()->findOrFail($conversation);
        $conversation->messages()->delete();
        $conversation->delete();

        return to_route('chat.index');
    }

    public function stream(Request $request): StreamableAgentResponse
    {
        $request->validate(['id' => ['required', 'uuid']]);

        // useChat sends the whole chat; Vercel::chat() reads it.
        $chat = Vercel::chat($request);
        $user = $request->user();

        $conversation = Conversation::find($request->id);

        // Somebody else's conversation? No.
        abort_if($conversation && ! $conversation->participant->is($user), 403);

        $isNew = $conversation === null;

        $conversation ??= $user->conversations()->create([
            'id' => $request->id,
            'title' => Str::limit($chat->message()?->content ?? 'New chat', 40),
        ]);

        return (new Assistant)
            ->continue($conversation->id, as: $user)
            ->stream($chat)
            ->then(function () use ($isNew, $conversation, $chat) {
                // First answer done: name the chat. If that fails, keep the old title.
                if ($isNew) {
                    rescue(fn () => $conversation->update([
                        'title' => Str::limit((new TitleWriter)->prompt($chat->message()->content)->text, 60),
                    ]));
                }
            })
            ->usingVercelDataProtocol($chat->messageId());
    }
}
