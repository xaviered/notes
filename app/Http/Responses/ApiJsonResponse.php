<?php
namespace App\Http\Responses;

use App\Database\Collections\ModelCollection;
use App\Database\Core\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ApiJsonResponse is a JsonResponse representation of a model
 * @package App\Http\Responses
 */
class ApiJsonResponse extends JsonResponse
{
	/**
	 * ApiJsonResponse constructor.
	 * @param Model|ModelCollection $model Model or collection
	 * @param int $status
	 * @param array $headers
	 * @param int $options
	 */
	public function __construct( $model = null, $status = 200, array $headers = [], $options = 0 ) {
		$response = [];
		if ( $model ) {
			// model(s)
			if ( $model instanceof Model ) {
				$response = $this->modelToApiArray( $model );
			}
			else if ( $model instanceof ModelCollection ) {
				$response = $this->collectionToApiArray( $model );

			}
			else {
				$response = [ 'data' => $model ];
			}
		}

		parent::__construct( $response, $status, $headers, $options );
	}

	/**
	 * API array representation of this collection
	 *
	 * @param ModelCollection $col
	 * @param int $relationsDepth Current depth of relations loaded. Default = 1
	 * @param bool $hideLinks Hide links section
	 * @param bool $withKeys Show keys for Collections
	 * @param bool $ignorePaging Will not load paging mechanism
	 * @return array
	 */
	public function collectionToApiArray( ModelCollection $col, $relationsDepth = -1, $hideLinks = false, $withKeys = false, $ignorePaging = false ) {
		$count = 0;
		$modelsArray = [];
		$paginator = $ignorePaging ? $col : $col->paginate();
		foreach ( $paginator as $itemKey => $item ) {
			if ( $item instanceof ModelCollection ) {
				$item = $this->collectionToApiArray( $item, $relationsDepth + 1, true, false, true )[ 'data' ] ?? [];
			}
			else if ( $item instanceof Model ) {
				$item = $this->modelToApiArray( $item, $relationsDepth + 1, true );
			}

			$modelsArray[ 'data' ][ $withKeys ? $itemKey : $count ] = $item;
			$count++;
		}

		// get url based on model
		$selfUrl = $col->getRootModel()->uri();
		$request = Request::create( $selfUrl );

		// remove page=0|1 param for caching performance
		if ( $request->query->get( 'page' ) <= 1 ) {
			$request->query->remove( 'page' );
//			$request->server->set( 'QUERY_STRING', Request::normalizeQueryString( http_build_query( $request->query->all() ) ) );
		}

		$modelsArray['meta'][ 'count' ] = $paginator->count();
		if ( !$ignorePaging && $paginator->hasPages() ) {
			$page = $paginator->currentPage();
			$paginator->setRootModel( $col->getRootModel() );

			if ( $request->query->count() ) {
				$parameters = $request->query->all();
				$paginator->appends( $parameters );
			}

			$modelsArray['meta'][ 'total_count' ] = $paginator->total();
			$modelsArray['meta'][ 'page' ] = $page;
			$modelsArray['meta'][ 'total_pages' ] = $paginator->lastPage();

			if ( !$hideLinks && $paginator->previousPageUrl() ) {
				if ( $page - 1 > 1 ) {
					$modelsArray[ 'links' ][ 'prev' ] = $paginator->previousPageUrl();
				}
				else {
					$modelsArray[ 'links' ][ 'prev' ] = $paginator->url( $page - 1 );
				}
			}
			if ( $paginator->hasMorePages() ) {
				$modelsArray[ 'links' ][ 'next' ] = $paginator->nextPageUrl();
			}
		}

		if ( !$hideLinks ) {
			// this is a "collection", so don't pass any params
			$r = Request::create( $col->getRootModel()->uri( 'show', [ '' ] ) );
			$modelsArray[ 'links' ][ 'self' ] = $request->query->count() ? $r->fullUrlWithQuery( $request->all() ) : $r->url();
		}

		return $modelsArray;
	}

	/**
	 * API array representation of this model
	 *
	 * @param Model $model
	 * @param int $relationsDepth Current depth of relations loaded. Default = 1
	 * @param bool $hideSelfLinkQuery Don't add query info to self link for Models
	 * @return array
	 */
	public function modelToApiArray( Model $model, $relationsDepth = 0, $hideSelfLinkQuery = false ) {
		// load relations
		$relations = [];

		if ( !request( 'ignore_relations' ) ) {
			if ( $relationsDepth < intval( request( 'relations_max_depth', 1 ) ) ) {
				$relationsApiArray = $this->collectionToApiArray(
					$model->getCollectionRelations(),
					$relationsDepth,
					true,
					true,
					true
				);
				$relations = $relationsApiArray[ 'data' ] ?? [];
			}
		}

		$r = Request::create( $model->uri( 'show' ) );

		$modelArray = [
			'data' => $model->attributesToArray(),
			'relations' => $relations,
			'links' => [
				'self' => $hideSelfLinkQuery ? $r->url() : $r->fullUrl()
			]
		];

		return $modelArray;
	}
}
