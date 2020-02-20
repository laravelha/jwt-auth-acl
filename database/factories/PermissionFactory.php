<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laravelha\Auth\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'verb' => $faker->word,
        'uri' => $faker->url,
    ];
});
