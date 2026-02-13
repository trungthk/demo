<?php

namespace App\Console\Commands;

use App\Jobs\TestFailQueue;
use App\Jobs\TestRetryQueue;
use App\Services\OrderService;
use Illuminate\Console\Command;

class TestQueue extends Command
{
    public function __construct(
        private readonly OrderService $orderService
    )
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-order {retry=0 : 0 - No Retry, 1 - Retry} {status=0 : 0 - Failure, 1 - Success} {type=1 : 1 - Not using queue, 2 - Using queue} {delay=0 : Delay in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);
        $type = $this->argument('type');
        $delay = $this->argument('delay');
        $status = $this->argument('status');
        $retry = $this->argument('retry');

        if($status == 0) {
            if($retry == 1) {
                $this->info('Using fail queue with retry');
                TestRetryQueue::dispatch();
            } else {
                $this->info('Using fail queue without retry');
                TestFailQueue::dispatch();
            }
        } else {
            $this->info($type == 1 ? 'Not using queue' : 'Using queue');
            $result = $this->orderService->newOrder($type, $delay);
            $this->info('Order created with delay: ' . $result . ' seconds');
        }

        $end = microtime(true);
        $this->info('Order created in ' . ($end - $start) . ' seconds');
    }
}
