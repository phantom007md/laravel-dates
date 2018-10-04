<?php

use Faker\Generator as Faker;

$factory->define(App\Discount::class, function (Faker $faker) {
    return [
        'discount' => $faker->numberBetween(10, 60),
        'code' => str_random(8),
        'active' => true,
        'expire' => new DateTime('tomorrow'),
    ];
});
