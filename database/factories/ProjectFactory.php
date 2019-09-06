<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProjectModel;
use Faker\Generator as Faker;

$factory->define(ProjectModel::class, function (Faker $faker) {
    return [
        'project_name' => $faker->name,
        'project_description' => $faker->text,
    ];
});
