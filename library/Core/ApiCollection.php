<?php
namespace notes\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Class ApiCollection creates a collection of APIModels from a notes API response
 *
 * @package notes\Core
 */
class ApiCollection extends Collection
{
	public function __construct( $apiResponse = [] ) {
		$this->loadAllPages( $apiResponse );
		unset( $apiResponse[ 'meta' ][ 'count' ] );
		unset( $apiResponse[ 'meta' ][ 'page' ] );

		foreach ( $apiResponse[ 'data' ] as $item ) {
			$this->push( new ApiModel( $item ) );
		}
	}

	/**
	 * Load data from all pages
	 *
	 * @param array $apiResponse
	 */
	protected function loadAllPages( &$apiResponse ) {
		$apiResponse[ 'data' ] = $apiResponse[ 'data' ] ?? [];
		if ( isset( $apiResponse[ 'links' ][ 'next' ] ) ) {
			$request = Request::create( $apiResponse[ 'links' ][ 'next' ], 'GET' );
			// @todo: Authentication will block this call. Make local call, or add ability to login.
			$nextPageResponse = json_decode( Route::dispatch( $request )->getContent() );
			if ( $nextPageResponse ) {
				$this->loadAllPages( $nextPageResponse );
				$apiResponse[ 'data' ] = array_merge( $apiResponse[ 'data' ], $nextPageResponse[ 'data' ] );
			}
		}
	}
}
