<?php
namespace App\Http\Controllers;

use App\Database\Models\User;
use App\Http\Responses\ApiJsonResponse;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends ModelController
{
	/**
	 * Display all notes for the given user
	 *
	 * @param User $user
	 * @return ApiJsonResponse
	 */
	public function notes( User $user ) {
		$notes = $user->notes()->get();

		return new ApiJsonResponse( $notes );
	}
}
