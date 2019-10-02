<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick Generate user token';

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
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->ask('please input user id');
        $user = User::find($userId);

        if(!$user)
        {
           return $this->error('User does\' not exists');
        }

        // 1 years later expired
        $ttl = 365 * 24 * 60;
        $this->info( \Auth::guard('api')->setTTL($ttl)->fromUser($user) );
    }
}
