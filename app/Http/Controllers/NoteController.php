<?php
namespace App\Http\Controllers;

use App\Database\Models\Note;
use App\Http\Responses\ApiJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class NoteController
 *
 * @package App\Http\Controllers
 */
class NoteController extends ModelController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return ApiJsonResponse
	 */
	public function index( Request $request ) {
		return new ApiJsonResponse(
			$this->getModelCollection( $request, Note::query() )
		);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Note $note
	 * @return JsonResponse
	 */
	public function show( Note $note ) {
		return $this->showModel( $note );
	}

	public function showUserNotes(User $user) {

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return ApiJsonResponse
	 */
	public function store( Request $request ) {

		$updates = $request->all();
		unset( $updates[ '_id' ] );

		$model = Note::create( $updates );
		$model->saveOrFail();

		return new ApiJsonResponse( $model );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param  Note $note
	 * @return JsonResponse
	 */
	public function update( Request $request, Note $note ) {
		$tags = $request->get( 'tags' );
		if ( !is_array( $tags ) ) {
			$tags = [ $tags ];
		}
		if ( $tags ) {
			$note->retag( $tags );
		}

		return parent::updateModel( $request, $note );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Note $note
	 * @return JsonResponse
	 */
	public function destroy( Note $note ) {
		return $this->destroyModel( $note );
	}
}
