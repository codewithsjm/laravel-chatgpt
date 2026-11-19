<?php

namespace App\Http\Controllers;

use App\Ai\Agents\Assistant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Vercel\Vercel;

class ChatController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('chat');
    }

    public function stream(Request $request): StreamableAgentResponse
    {
        // useChat sends the whole chat; Vercel::chat() reads it.
        $chat = Vercel::chat($request);

        return (new Assistant)
            ->stream($chat)
            ->usingVercelDataProtocol($chat->messageId());
    }
}
