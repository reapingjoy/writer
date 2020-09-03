<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Note;
use Faker\Generator as Faker;

//Define the note factory using Faker
$factory->define(Note::class, function (Faker $faker) {
    return [
        'title' => $faker->text,
        'body' => $faker->paragraphs(2, true),
        'created_at' => $faker->dateTimeBetween('-3 months'),
    ];
});
