<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Confmember;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Confmember::class, function (Faker $faker) {
    return [
        'user_id' => $faker->user_id,
        'user_name' => $faker->user_name,
        'conference_id' => $faker->conference_id,
    ];
});
