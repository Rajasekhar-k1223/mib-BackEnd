<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\feedsController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\EmailSystemController;
use App\Http\Controllers\defaultSettingsController;
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
Route::post('/newUser',[UsersController::class,'store']);
Route::get('/UserCreate',[UsersController::class,'getAll']);
//Route::get('/getUser',[UsersController::class,'getUser']);
Route::get('/getUser/{userId}',[UsersController::class,'getUser']);
//Route::get('/feeds',[feedsController::class,'getAll']);


// Route::middleware('auth:api')->get('/feeds',[feedsController::class,'getAll']);
Route::middleware('auth:api')->post('/feeds',[feedsController::class,'getAll']);
Route::middleware('auth:api')->post('/feedsNonCache',[feedsController::class,'getAllFeeds']);
Route::middleware('auth:api')->post('/changeUserHeaderColor',[UsersController::class,'changeUserHeaderColor']);
Route::middleware('auth:api')->get('/getUser/{userId}',[UsersController::class,'getUser']);
Route::middleware('auth:api')->get('/defaultsettings',[defaultSettingsController::class,'getSettings']);
Route::middleware('auth:api')->get('/getAllLikes',[feedsController::class,'getAllLikes']);
Route::middleware('auth:api')->get('/GetfeedData/{feedId}',[feedsController::class,'GetfeedData']);
Route::middleware('auth:api')->post('/SendMessageToFriend',[FriendsController::class,'SendMessageToFriend']);
Route::middleware('auth:api')->get('/GetMessagesFromFriends',[FriendsController::class,'GetMessagesFromFriends']);
Route::middleware('auth:api')->get('/getFriendsList',[FriendsController::class,'getFriendsList']);
Route::middleware('auth:api')->get('/getFriendDetails',[FriendsController::class,'getFriendDetails']);
Route::get('/getAllUsers',[UsersController::class,'getAllUsers']);
Route::middleware('auth:api')->get('/streamVideo',[feedsController::class,'stream']);
Route::middleware('auth:api')->get('/findFriendOrPage',[FriendsController::class,'findFriendOrPage']);
Route::middleware('auth:api')->get('/FriendRequestFromCheck',[FriendsController::class,'FriendRequestFromCheck']);
Route::middleware('auth:api')->get('/friendRequestAcceptance',[FriendsController::class,'friendRequestAcceptance']);
Route::middleware('auth:api')->get('/CheckListNotification',[FriendsController::class,'CheckListNotification']);
// Route::get('/streamVideo',[feedsController::class,'stream']);
Route::middleware('auth:api')->post('/NewFeed',[feedsController::class,'createFeed']);
Route::middleware('auth:api')->post('/CheckLikes',[feedsController::class,'CheckLikes']);
Route::middleware('auth:api')->post('/CheckdisLikes',[feedsController::class,'CheckdisLikes']);
Route::middleware('auth:api')->post('/emojismileAdd',[feedsController::class,'emojismileAdd']);
Route::middleware('auth:api')->post('/FriendRequestFrom',[FriendsController::class,'FriendRequestFrom']);

Route::post('/registration',[UsersController::class,'registration']);
Route::post('/login',[UsersController::class,'login']);
Route::get('/login',[UsersController::class,'login']); //
Route::get('/getAllEMessages',[EmailSystemController::class,'getAllEMessages']); //
Route::middleware('auth:api')->get('/logout',[UsersController::class,'logout']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
