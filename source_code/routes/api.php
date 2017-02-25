<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use App\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * list all post with GET request
 */
Route::get ( '/post', 'PostController@ListPost');

/**
 * create post with PUT request
 */
Route::put ( '/post','PostController@PutPost');

/**
 * update post with POST request
 */
Route::post ( '/post','PostController@UpdatePost');

/**
 * delete post with DELETE request
 */
Route::delete ( '/post','PostController@DeletePost');


/**
 * ******************************************************************
 */

/**
 * list all tag with GET request
 */
Route::get ( '/tag','TagController@ListTag');

/**
 * create tag with PUT request
 */
Route::put ( '/tag','TagController@CreateTag');

/**
 * update tag with POST request
 */
Route::post ( '/tag','TagController@UpdateTag');

/**
 * delete tag with DELETE request
 */
Route::delete ( '/tag','TagController@DeleteTag');

/***************** additional method *********/

/**
 * tag to post with POST request
 */
Route::post ( '/tagonpost','TagController@TagOnPost');

/**
 * show posts by tag or tags with POST request
 */
Route::post ( '/showpost','TagController@ShowPost');
							
/**
 * count all posts by tag or tags with POST request
 */
Route::post ( '/postcount','TagController@CountPostByTag');
