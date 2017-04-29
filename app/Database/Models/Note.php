<?php
namespace App\Database\Models;

use App\Database\Core\Model;
use App\User;

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
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'message'
	];

	/**
	 * Get the user that created this note
	 */
	public function user() {
		return $this->belongsTo( User::class );
	}

	/**
	 * Add tag(s) to this note
	 *
	 * @param string|string[] $tags
	 * @return $this
	 */
	public function tag( $tags ) {
		if ( !is_array( $tags ) ) {
			$tags = [ $tags ];
		}

		$noteTags = [];
		// add new tags, use existing ones
		foreach ( $tags as $tag ) {
			$tag = Tag::query()->firstOrCreate( [
				'name' => $tag,
				'slug' => Tag::makeSlug( $tag ),
			] )
			;
			if ( $tag && !isset( $noteTags[ $tag->id ] ) ) {
				$noteTags[ $tag->id ] = $tag;
			}
		}

		$this->tags()->saveMany($noteTags);

		return $this;
	}

	/**
	 * Clears all tags and adds the given ones
	 *
	 * @param string|string[] $tags
	 * @return $this
	 */
	public function retag( $tags ) {
		$this->tags()->detach();

		return $this->tag( $tags );
	}

	/**
	 * Query to get tags attached to this Note
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tags() {
		return $this->belongsToMany( Tag::class, 'note_tags' );
	}
}
