<?php


namespace App\Models\Traits;


use Carbon\Carbon;
use Redis;

trait LastActivedAtHelper
{
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        $date = Carbon::now()->toDateString();

        // Redis hash name example: larabbs_last_actived_at_2019_07_20
        $hash = $this->getHashFromDateString($date);

        // filed name e.g: user_1
        $field = $this->getHashField();

        // current time
        $now = Carbon::now()->toDateTimeString();

        // writing to redis
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // get yesterday date
        //$yesterday_date = Carbon::yesterday()->toDateString();
        $yesterday_date = Carbon::now()->toDateString();

        // get hash list from redis
        $hash = $this->getHashFromDateString($yesterday_date);
        $dates = Redis::hGetAll($hash);

        foreach($dates as $user_id => $actived_at )
        {
            //  convert filed user_1 to 1
            $user_id = str_replace($this->field_prefix, '', $user_id);
            if( $user = $this->find($user_id) )
            {
                $user->last_actived_at = $actived_at;
                $user->save();
            }

        }

        Redis::del($hash);
    }

    //  read filed atribute
    public function getLastActivedAtAttribute($value)
    {
        // get to day date
        $date = Carbon::now()->toDateString();

        // Redis hash table name: larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString($date);

        // filed name e.g: user_1
        $field = $this->getHashfield();

        // first get data from redis otherwise use database
        $datetime = Redis::hGet($hash, $field) ? : $value;

        if($datetime)
        {
           return new Carbon($datetime);
        }
        else {
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        // Redis hash table name: larabbs_last_actived_at_2017-10-21
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
       return $this->field_prefix . $this->id;
    }
}