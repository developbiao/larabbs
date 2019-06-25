<?php


namespace App\Models\Traits;


use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelper
{
    // save temporary users data
    protected $users = [];

    // configure information
    protected $topic_weight = 4; // topic weight
    protected $reply_weight = 1; // reply weight
    protected $pass_days = 7;    // how many day pass
    protected $user_number = 6;   // get how many users

    // about cache configuration
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers()
    {
        // try to get active from cache
        // if not found will get data from database and cache it
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAdnCacheActiveUsers()
    {
        // get active user list
        $active_users = $this->calculateActiveUsers();

        // cache it
        $this->cacheActiveUsers($active_users);

    }


    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // array sort by score
        $users = array_sort($this->users, function($user){
            return $user['score'];
        });

        $users = array_reverse($users, true);

        // get we are need rows
        $users = array_slice($users, 0, $this->user_number, true);

        // create empty collect
        $active_users = collect();

        foreach($users as $user_id => $user)
        {
            $user = $this->find($user_id);
            if(count($user))
            {
               $active_users->push($user) ;
            }

        }

        return $active_users;
    }


    private function calculateTopicScore()
    {
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) AS topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDay($this->pass_days))
            ->groupBy('user_id')
            ->get();

        // calculate topic score
        foreach($topic_users as $value)
        {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;

        }

    }

    private function calculateReplyScore()
    {
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDay($this->pass_days))
            ->groupBy('user_id')
            ->get();

        foreach($reply_users as $value)
        {
            $reply_score = $value->reply_count * $this->reply_weight;
            if( isset( $this->users[$value->user_id['score']] ) )
            {
               $this->users['score'] += $reply_score;
            }
            else
            {
               $this->users[$value->user_id]['score'] = $reply_score;
            }

        }

    }

    private function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }

}