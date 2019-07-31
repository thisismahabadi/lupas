<?php

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

$factory->define(App\Models\User\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => Illuminate\Support\Facades\Hash::make($faker->password),
    ];
});

$factory->define(App\Models\Post\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'description' => $faker->paragraph,
    ];
});