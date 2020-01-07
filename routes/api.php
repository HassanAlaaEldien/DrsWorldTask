<?php

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

use Illuminate\Support\Facades\Route;

// Feedback Routes
Route::group(['prefix' => 'feedbacks'], function () {
    Route::get('/', 'FeedbackController@index')->name('feedback.index');

    Route::post('/', 'FeedbackController@store')->name('feedback.store');

    Route::get('count', 'FeedbackController@count')->name('feedback.count');

    Route::get('{number}', 'FeedbackController@show')->name('feedback.show');
});
