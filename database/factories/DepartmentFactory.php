<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Department;
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

$factory->define(Department::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'companyid' => $faker->companyid,
        'depid1' => $faker->depid1,
        'depname1' => $faker->depname1,
        'depid2' => $faker->depid2,
        'depname2' => $faker->depname2,
        'depid3' => $faker->depid3,
        'depname3' => $faker->depname3,
    ];
});
