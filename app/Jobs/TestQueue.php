<?php

namespace App\Jobs;

use App\Services\LogService;
use App\Services\LoyaltyService;
use App\Services\WarehouseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestQueue implements ShouldQueue
{
    use Queueable;
    public $logService;
    public $warehouseService;
    public $loyaltyService;

    /**
     * Create a new job instance.
     */
    public function __construct(LogService $logService, WarehouseService $warehouseService, LoyaltyService $loyaltyService)
    {
        $this->logService = $logService;
        $this->warehouseService = $warehouseService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // ghi log
        $this->logService->newLog('New order created');
        // trừ kho
        $this->warehouseService->decreaseStock();
        // tích điểm
        $this->loyaltyService->addPoints();
    }
}
