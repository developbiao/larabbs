<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    $sentence = $faker->sentence();
    // random a month date
    $updated_at = $faker->dateTimeThisMonth();
    // created time less updated time
    $created_at = $faker->datetimeThisMonth($updated_at);
    return [
        'title'      => $faker->sentence,
        'body'       => $faker->text(),
        'excerpt'    => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at
    ];
});
