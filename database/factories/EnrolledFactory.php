<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enrolled;
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

$factory->define(Enrolled::class, function (Faker $faker) {
    return [
        'user_id' => $faker->user_id,
        'company_id' => $faker->company_id,
        'department_id' => $faker->department_id,
        'countadminflg' => $faker->countadminflg,
        'depadminflg' => $faker->depadminflg,
        'compadminflg' => $faker->compadminflg,
    ];
});
