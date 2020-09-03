<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Draft;
use Faker\Generator as Faker;

//Define the draft factory using Faker
$factory->define(Draft::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(10),
        'alias' => $faker->unique()->name,
        'short_description' => $faker->paragraphs(5, true),
        'created_at' => $faker->dateTimeBetween('-3 months'),
    ];
});
