<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laravelha\Auth\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->text(),
        'action' => $faker->text(),
        'description' => $faker->text(),
    ];
});
