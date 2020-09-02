<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');

Route::group([
    'middleware' => 'auth:api'
], function() {
    Route::get('logout', 'API\AuthController@logout');
    Route::get('user', 'API\AuthController@user');

    Route::get('drafts/deleted', 'API\DraftController@getAllDeleted');
    // Route::get('notes/deleted', 'API\NotesController@getAllDeleted');
    
    Route::apiResources([
        'drafts' => 'API\DraftController',
        'notes' => 'API\NoteController',
    ]);
    
    

});