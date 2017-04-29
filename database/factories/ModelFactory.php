<?php

use App\Database\Models\User;
use App\Database\Models\Note;
use App\Database\Models\Tag;
use Faker\Generator as Faker_Generator;
use Illuminate\Database\Eloquent\Factory as Eloquent_Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// users
/** @var Eloquent_Factory $factory */
$factory->define( User::class, function( Faker_Generator $faker ) {
	static $password;

	return [
		'name' => 'Notes User',
		'username' => "notes_user",
		'email' => "xaviered@gmail.com",
		'password' => $password ?: $password = bcrypt( 'secret' ),
		'remember_token' => str_random( 10 ),
	];
} );

// sample note
/** @var Eloquent_Factory $factory */
$factory->define( Note::class, function( Faker_Generator $faker ) {
	return [
		'title' => 'Note ' . $faker->words(3, 1),
		'message' => $faker->text( 50 ),
		'user_id' => auth()->user()->id ?? 1
	];
} );

// sample tag
/** @var Eloquent_Factory $factory */
$factory->define( Tag::class, function( Faker_Generator $faker ) {
	return [
		'name' => $faker->name
	];
} );
