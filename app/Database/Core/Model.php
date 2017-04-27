<?php
namespace App\Database\Core;

use App\Database\Collections\ModelCollection;
use Illuminate\Database\Eloquent\Model as Moloquent;

/**
 * Class Model is the base for all Models under this app
 *
 * @package App\Models
 */
abstract class Model extends Moloquent
{
	/**
	 * The storage format of the model's date columns.
	 *
	 * @var string
	 */
	protected $dateFormat = 'U';

	/**
	 * @return ModelCollection
	 */
	public function getCollectionRelations() {
		return $this->newCollection( $this->getRelations() );
	}

	/**
	 * Create a new Eloquent Collection instance.
	 *
	 * @param  Model[] $models
	 * @return ModelCollection
	 */
	public function newCollection( array $models = [] ) {
		$col = new ModelCollection( $models );
		$col->setRootModel( $this );

		return $col;
	}

	/**
	 * Gets index URL of current model
	 *
	 * @param string $action Route action to get
	 * @param array $parameters
	 * @return string
	 */
	public function uri( $action = 'index', $parameters = [] ) {
		if ( empty( $parameters ) ) {
			$parameters = request()->query->all();
		}

		switch ( $action ) {
			case 'show':
				unset( $parameters[ 'page' ] );
				unset( $parameters[ 'page_size' ] );
				break;
		}

		// get url based on model
		return url()->route( static::ROUTE_NAME . '.' . $action, $parameters );
	}
}
