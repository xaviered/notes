<?php

namespace Tests\Feature;

use App\Database\Models\Note;
use App\Database\Models\Tag;
use notes\Core\ApiCollection;
use Tests\TestCase;

class ApiNoteTest extends TestCase
{
	// disable authentication
	use withoutMiddleware;

	/** @var Note */
	protected $sampleNote;

	/**
	 * Adds dependencies
	 */
	public function setUp() {
		parent::setUp();

		$tags = factory( Tag::class, 20 )->make()->pluck( 'name' );
		$this->sampleNote = factory( Note::class )->make( [ 'user_id' => 1 ] );
		$this->sampleNote->setRelation( 'tags', $tags );
	}

	/**
	 * Tests creation of a note against the DB
	 */
	public function testCreateNote() {
		// post to API
		$attributes = $this->sampleNote->getAttributes();
		$attributes[ 'tags' ] = $this->sampleNote->getRelation( 'tags' )->all();
		$response = $this->call( 'POST', '/api/notes', $attributes );
		$response->assertStatus( 200 );

		// get ID
		$id = $response->json()[ 'data' ][ 'id' ];
		$this->assertGreaterThan( 0, $id, "Could not create proper note." );
		$this->sampleNote->id = $id;

		// load from DB
		$dbNote = Note::find( $id );
		$dbAttributes = $dbNote->getAttributes();
		unset( $dbAttributes[ 'created_at' ] );
		unset( $dbAttributes[ 'updated_at' ] );

		// compare
		$this->assertEquals( $dbAttributes, $this->sampleNote->getAttributes(), "Note not the same on DB." );
	}

	/**
	 * Tests /api/notes API endpoint gets all content from DB
	 */
	public function testListNotes() {
		$dbNotes = Note::query()->where( 'user_id', null, 1 )->get()->keyBy( 'id' );
		$apiResponse = $this->get( '/api/users/1/notes' )->json();

		$this->assertNotNull( $apiResponse, "No API response" );

		$count = $apiResponse[ 'meta' ][ 'total_count' ] ?? $apiResponse[ 'meta' ][ 'count' ] ?? 0;
		$this->assertTrue( $dbNotes->count() == $count, "Notes in DB are not the same as in API response." );

		$col = new ApiCollection( $apiResponse );

		foreach ( $col as $apiModel ) {
			$this->assertNotNull( $dbNotes->pull( $apiModel->id ), "Note {$apiModel->id} is in API but not in DB." );
		}

		$this->assertTrue( $dbNotes->count() == 0, $dbNotes->count() . " note(s) found in DB but not in API: [" . $dbNotes->implode( 'id', ', ' ) . "]." );
	}

	/**
	 * Edit note and test change on DB
	 */
	public function testEditNote() {
		// make this change
		$newTitle = 'New title';

		// Post to API
		$response = $this->call( 'PATCH', '/api/notes/' . $this->sampleNote->id, [ 'title' => $newTitle ] );
		$response->assertStatus( 200 );

		// load from db
		$dbNote = Note::find( $this->sampleNote->id );

		// compare
		$this->assertEquals( $dbNote->title, $newTitle, "Title in DB was not updated." );
	}

	/**
	 * Delete note and test change on DB
	 */
	public function testDeleteNote() {
		// make this change
		$newTitle = 'New title';

		// Post to API
		$response = $this->call( 'DESTROY', '/api/notes/' . $this->sampleNote->id );
		$response->assertStatus( 200 );

		// load from db
		$dbNote = Note::find( $this->sampleNote->id );

		// compare
		$this->assertNull( $dbNote, "Note was not removed from DB." );
	}

}
