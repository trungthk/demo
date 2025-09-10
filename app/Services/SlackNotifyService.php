<?php

namespace App\Services;

use App\Contracts\NotifyInterface;
use Illuminate\Support\Facades\Log;

class SlackNotifyService implements NotifyInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function send(string $message): void
    {
        Log::info("Slack notification sent: {$message}");
    }
}
