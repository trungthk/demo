<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WarehouseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function decreaseStock() {
        sleep(1);
        Log::info('Stock decreased');
    }
}
