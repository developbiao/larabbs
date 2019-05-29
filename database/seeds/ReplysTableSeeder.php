<?php

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $user_ids = User::all()->pluck('id')->toArray();

        // all topic ids
        $topic_ids = Topic::all()->pluck('id')->toArray();
        // get Faker instance
        $faker = app(Faker\Generator::class);
        $replys = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use($user_ids, $topic_ids, $faker){
                $reply->user_id = $faker->randomElement($user_ids);
                $reply->topic_id = $faker->randomElement($topic_ids);
            });

        Reply::insert($replys->toArray());
    }

}

