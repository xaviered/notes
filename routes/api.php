<?php

use App\Database\Models\Note;
use App\Database\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( 'auth:api' )->get( '/user', function( Request $request ) {
	return $request->user();
} )
;

// All CRUD methods for Note model
Route::middleware( 'customAuth' )->resource( Note::ROUTE_NAME, 'NoteController' );

// Specific endpoint to get user's notes
Route::middleware( 'customAuth' )->get( User::ROUTE_NAME . '/{user}/notes', 'UserController@notes' );