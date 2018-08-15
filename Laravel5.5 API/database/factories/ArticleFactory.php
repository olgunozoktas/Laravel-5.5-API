<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        //to format the fake data
        'title' => $faker->text(50), //50 characters
        'body'  => $faker->text(200) 
    ];
});
