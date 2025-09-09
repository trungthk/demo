<?php

namespace App\Services;

use App\Contracts\NotifyInterface;
use Illuminate\Support\Facades\Log;

class EmailNotifyServer implements NotifyInterface
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
        Log::warning("Email notification sent: {$message}");
    }
}
