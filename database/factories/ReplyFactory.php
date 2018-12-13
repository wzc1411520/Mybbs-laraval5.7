<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    //随机时间
    $time =  $faker->dateTimeThisMonth();
    return [
        // 'name' => $faker->name,
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
