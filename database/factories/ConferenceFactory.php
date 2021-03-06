<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Conference;
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

$factory->define(Conference::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'company_id' => $faker->company_id,
        'username' => $faker->username,
        'secret' => $faker->secret,
        'password' => $faker->password,
        'innerflg' => $faker->innerflg,
        'status' => $faker->status,
        'schedule' => $faker->schedule,
    ];
});
