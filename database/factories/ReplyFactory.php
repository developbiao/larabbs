<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    // random get this month
    $time = $faker->dateTimeThisMonth();
    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time
    ];
});
