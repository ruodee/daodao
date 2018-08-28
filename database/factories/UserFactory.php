<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    $date_time = $faker->date.' '.$faker->time;
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'is_admin' => false,
        'password' => $password ?:$password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'created_at' => $date_time,
        'updated_at' => $date_time,
        'activated' => true,
    ];
});
