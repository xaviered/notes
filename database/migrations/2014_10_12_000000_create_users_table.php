<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'users', function( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'name' );
			$table->string('username')->unique();
			$table->string( 'email' )->unique();
			$table->string( 'password' );
			$table->rememberToken();
			$table->timestamps();
		} );

		factory( \App\Database\Models\User::class )->create( [ 'id' => 1 ] );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'users' );
	}
}
