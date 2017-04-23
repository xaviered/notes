<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Moloquent;

/**
 * Class Model is the base for all Models
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
}
