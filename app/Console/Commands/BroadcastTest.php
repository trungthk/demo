<?php

namespace App\Console\Commands;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Console\Command;

class BroadcastTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast-test';

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
        broadcast(new UserCreated(User::first()));
        $this->info('Broadcasted UserCreated event.');
    }
}
