<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

// A tiny job, so the cheapest (and fastest) model does it.
#[UseCheapestModel]
class TitleWriter implements Agent
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return 'Write a 3 to 5 word title for a chat that starts with this message. '
            .'Reply with the title only: no quotes, no full stop.';
    }
}
