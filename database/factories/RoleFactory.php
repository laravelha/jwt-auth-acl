<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laravelha\Auth\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->text(),
        'description' => $faker->text(),
    ];
});
