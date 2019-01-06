<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Link::class, function (Faker\Generator $faker) {
    return [
        'original_url' => $faker->url
    ];
});
