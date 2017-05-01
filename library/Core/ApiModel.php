<?php
namespace notes\Core;

/**
 * Class ApiModel creates a simple representation of a model from a notes API response
 *
 * @package notes\Core
 */
class ApiModel
{
	/** @var mixed[] */
	public $attributes;

	/** @var mixed[] */
	protected $links;

	/** @var mixed[] */
	protected $relations;

	/**
	 * ApiModel constructor.
	 *
	 * @param array $apiResponse
	 */
	public function __construct( array $apiResponse = [] ) {
		$this->fixApiResponse( $apiResponse );

		$this->setAttributes( $apiResponse[ 'data' ] );
		$this->setRelations( $apiResponse[ 'relations' ] );
		$this->setLinks( $apiResponse[ 'links' ] );
	}

	protected function fixApiResponse( &$apiResponse ) {
		$apiResponse[ 'data' ] = $apiResponse[ 'data' ] ?? [];
		$apiResponse[ 'relations' ] = $apiResponse[ 'relations' ] ?? [];
		$apiResponse[ 'links' ] = $apiResponse[ 'links' ] ?? [];
	}

	/**
	 * @return mixed[]
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param mixed[] $attributes
	 */
	public function setAttributes( $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * @return \mixed[]
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 * @param \mixed[] $links
	 */
	public function setLinks( array $links ) {
		$this->links = $links;
	}

	/**
	 * @return \mixed[]
	 */
	public function getRelations(): array {
		return $this->relations;
	}

	/**
	 * @param \mixed[] $relations
	 */
	public function setRelations( array $relations ) {
		$this->relations = $relations;
	}

	/**
	 * Magic method to get attributes.
	 *
	 * @param string $attribute
	 * @return mixed|null
	 */
	public function __get( $attribute ) {
		if ( isset( $this->attributes[ $attribute ] ) ) {
			return $this->attributes[ $attribute ];
		}

		return null;
	}
}
