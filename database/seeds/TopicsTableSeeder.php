<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // get all user ids
        $user_ids = User::all()->pluck('id')->toArray();
        // get all category ids
        $category_ids = Category::all()->pluck('id')->toArray();
        // get faker instance
        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)
            ->times(100)
            ->make()
            ->each(function ($topic, $index) use($user_ids, $category_ids, $faker) {
                $topic->user_id   = $faker->randomElement($user_ids);
                $topic->category_id = $faker->randomElement($category_ids);
        });

        Topic::insert($topics->toArray());
    }

}

