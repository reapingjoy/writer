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

//Routes without authentication
Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');

//Routes that need token and are under auth middleware
Route::group([
    'middleware' => 'auth:api'
], function() {

    //Logout
    Route::get('logout', 'API\AuthController@logout');

    //Get All Soft Deleted Drafts
    Route::get('drafts/deleted', 'API\DraftController@getAllDeleted');

    //Get All Soft Deleted Notes
    Route::get('drafts/{draft_id}/notes/deleted', 'API\NoteController@getAllDeleted');

    //Share a specific draft with other users
    Route::post('drafts/{draft_id}/share', 'API\DraftController@shareDraft');

    //List all drafts shared with logged user 
    Route::get('drafts/shared', 'API\DraftController@getShared');

    //All Resource routes for drafts and notes
    Route::apiResources([
        'drafts' => 'API\DraftController',
        'drafts.notes' => 'API\NoteController',
    ]);
    
    

});