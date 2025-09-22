<?php

namespace App\Services;

use App\Jobs\TestQueue;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly LogService $logService,
        private readonly WarehouseService $warehouseService,
        private readonly LoyaltyService $loyaltyService
    )
    {
    }
    
    /**
     * Create a new order
     *
     * @param int $type
     * @param int $delay
     * @return void
     */
    public function newOrder(int $type, int $delay = 0) {
        sleep(2);
        Log::info('New order created');
        if($type == 1) { // not using queue
            // ghi log
            $this->logService->newLog('New order created');
            // trừ kho
            $this->warehouseService->decreaseStock();
            // tích điểm
            $this->loyaltyService->addPoints();
        } else { // using queue
            TestQueue::dispatch($this->logService, $this->warehouseService, $this->loyaltyService)->delay(now()->addSeconds($delay));
        }
        return $delay;
    }
}
