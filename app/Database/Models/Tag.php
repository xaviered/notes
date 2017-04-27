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
	 * Notes tagged by this Tag
	 */
	public function notes() {
		return $this->belongsToMany( Note::class, 'note_tags' );
	}
}
