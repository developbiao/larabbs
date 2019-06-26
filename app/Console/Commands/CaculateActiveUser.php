<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CaculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate active users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param $user
     * @return mixed
     */
    public function handle(User $user)
    {
        $this->info("starting calculate...");
        $user->calculateAndCacheActiveUsers();
        $this->info("Generator success!");
    }
}
