<?php
namespace App\Database\Collections;

use App\Database\Core\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Class ModelCollection serves as a custom collection for models
 *
 * @package App\Models
 */
class ModelCollection extends Collection
{
	/** @var int Items per page */
	protected static $perPage;

	/** @var \App\Database\Core\Model */
	protected $rootModel;

	/**
	 * @return \App\Database\Core\Model
	 */
	public function getRootModel() {
		return $this->rootModel;
	}

	/**
	 * @param Model $rootModel
	 */
	public function setRootModel( $rootModel ) {
		$this->rootModel = $rootModel;
	}

	/**
	 * @return int
	 */
	public function getPerPage() {
		if ( !isset( static::$perPage ) ) {
			$this->setPerPage( Config::get( 'pagination_size' ) ?? 1000 );
		}

		return static::$perPage;
	}

	/**
	 * @param int $perPage
	 */
	public function setPerPage( $perPage ) {
		static::$perPage = $perPage;
	}

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int $perPage
	 * @return Paginator
	 */
	public function paginate( $perPage = null ) {
		$page = Paginator::resolveCurrentPage();
		$perPage = $perPage ?: $this->getPerPage();

		$resultsCol = $this->slice( ( $page - 1 ) * $perPage, $perPage );

		//Create our paginator and pass it to the view
		return new Paginator( $resultsCol, $this->count(), $perPage );
	}
}
