<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function addPoints() {
        sleep(1);
        Log::info('Points added');
    }
}
