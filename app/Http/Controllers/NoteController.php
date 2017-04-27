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
	 * @param  Note $app
	 * @return JsonResponse
	 */
	public function show( Note $app ) {
		return $this->showModel( $app );
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
	 * @param  Note $app
	 * @return JsonResponse
	 */
	public function update( Request $request, Note $app ) {
		return parent::updateModel( $request, $app );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Note $app
	 * @return JsonResponse
	 */
	public function destroy( Note $app ) {
		return $this->destroyModel( $app );
	}
}
