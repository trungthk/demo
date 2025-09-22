<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestFailQueue implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * php artisan queue:retry <job_id>|all => retry failed job
     * php artisan queue:failed => list all failed jobs
     * php artisan queue:forget <job_id> => remove a failed job
     * php artisan queue:flush => remove all failed jobs
     */
    public function handle(): void
    {
        Log::info('Test fail queue at '.date('Y-m-d H:i:s'));
        throw new \Exception('Job failed');
    }
}
