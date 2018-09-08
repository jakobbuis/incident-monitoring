<?php

$factory->define(\App\Incident::class, function (Faker\Generator $faker) {
    return [
        'website_id' => function() {
            return factory(\App\Website::class)->create()->id;
        },
        'type' => $faker->randomElement(['SiteDown', 'CertificateError']),
        'level' => $faker->numberBetween(1, 4),
    ];
});
