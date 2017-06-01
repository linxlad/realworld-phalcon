<?php

use RealWorld\Models\Articles;
use RealWorld\Models\Comments;
use RealWorld\Models\Tags;
use RealWorld\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (\Faker\Generator $faker) {

    return [
        'username' => str_replace('.', '', $faker->unique()->userName),
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret',
        'bio' => $faker->sentence,
        'image' => 'https://help.fortrabbit.com/media/phalcon-mark.png',
    ];
});

$factory->define(Articles::class, function (\Faker\Generator $faker) {

    return [
        'title' => $faker->sentence(4),
        'description' => $faker->sentence(10),
        'body' => $faker->paragraphs($faker->numberBetween(1, 3), true),
    ];
});

$factory->define(Comments::class, function (\Faker\Generator $faker) {
    $users = User::find();
    $users = array_rand($users);

    return [
        'body' => $faker->paragraph($faker->numberBetween(1, 5)),
        'user_id' => $users->getFirst()->id,
    ];
});

$factory->define(Tags::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
