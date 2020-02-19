<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Laravelha\Auth\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->text(),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $faker->date(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => $faker->text(100),
    ];
});
