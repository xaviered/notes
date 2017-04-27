<?php
namespace App\Database\Models;

use App\Database\Core\Model;

/**
 * Class Note is a model representation of a note record
 *
 * @package App\Models
 */
class Note extends Model
{
	/**
	 * Routing relationship for this model
	 */
	const ROUTE_NAME = 'notes';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	protected $table = 'notes';

	/**
	 * Tags attached to this Note
	 */
	public function tags() {
		return $this->belongsToMany( Tag::class, 'note_tags' );
	}
}
