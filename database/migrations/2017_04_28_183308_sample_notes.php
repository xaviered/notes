<?php

use App\Database\Models\Note;
use App\Database\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Migrations\Migration;

class SampleNotes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		/** @var Collection $tags */
		factory( Note::class, 10 )->create()
			->each( function( Note $note ) {
				$tags = factory( Tag::class, rand( 1, 7 ) )->make()->pluck( 'name' )->all();
				$note->tag( $tags );
			} )
		;
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::table( 'notes' )->truncate();
	}
}
