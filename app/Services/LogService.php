<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function newLog($message) {
        sleep(1);
        Log::info($message);
    }
}
