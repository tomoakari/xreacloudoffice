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
        'userid' => $faker->userid,
        'companyid' => $faker->companyid,
        'departmentid' => $faker->departmentid,
        'countadminflg' => $faker->countadminflg,
        'depadminflg' => $faker->depadminflg,
        'compadminflg' => $faker->compadminflg,
    ];
});
