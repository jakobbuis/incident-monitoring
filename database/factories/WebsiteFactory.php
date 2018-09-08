<?php

$factory->define(\App\Website::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'url' => $faker->url,
    ];
});
