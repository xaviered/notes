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
		$tags = factory( Tag::class, 20 )->make();
		factory( Note::class, 10 )->create()
			->each( function( Note $note ) use ( $tags ) {
				$note->tag( $tags->random( rand( 1, 10 ) )->pluck( 'name' )->all() );
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
