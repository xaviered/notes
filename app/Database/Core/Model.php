<?php
namespace App\Database\Core;

use App\Database\Collections\ModelCollection;
use Illuminate\Database\Eloquent\Model as Moloquent;

// @todo: Validate, sanitize and clean user input before sending to database
/**
 * Class Model is the base for all Models under this app
 *
 * @package App\Models
 */
abstract class Model extends Moloquent
{
	/**
	 * @param bool $force Reload relations
	 *
	 * @return ModelCollection
	 */
	abstract public function getCollectionRelations( $force = false ): ModelCollection;

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
				if ( empty( $parameters[ 'id' ] ) ) {
					$parameters[ 'id' ] = $this;
				}
				unset( $parameters[ 'page' ] );
				unset( $parameters[ 'page_size' ] );
				break;
		}

		// get url based on model
		return url()->route( static::ROUTE_NAME . '.' . $action, $parameters );
	}
}
