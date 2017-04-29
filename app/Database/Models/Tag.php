<?php
namespace App\Database\Models;

use App\Database\Core\Model;

/**
 * Class Tag is a model representation of a Tag record
 *
 * @package App\Models
 */
class Tag extends Model
{
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	protected $table = 'tags';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'slug',
	];

	/**
	 * Given a $name will return its slug equivalent
	 *
	 * Tags have a special slug
	 *
	 * @param string $name
	 * @return string
	 */
	public static function makeSlug( $name ) {
		return strtolower( preg_replace( '/\s+/', '_', $name ) );
	}

	/**
	 * Notes tagged by this Tag
	 */
	public function notes() {
		return $this->belongsToMany( Note::class, 'note_tags' );
	}
}
