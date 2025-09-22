<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestRetryQueue implements ShouldQueue
{
    use Queueable;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine number of times the job may be attempted.
     */
    // public function tries(): int
    // {
    //     return 5;
    // }

    // /**
    //  * Determine the time at which the job should timeout.
    //  */
    // public function retryUntil(): DateTime
    // {
    //     return now()->addMinutes(10);
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Test retry queue at '.date('Y-m-d H:i:s'));
        throw new \Exception('Error Processing Request');
    }
}
