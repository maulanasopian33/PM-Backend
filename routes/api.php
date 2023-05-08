<?php

use App\Http\Controllers\APi\adminController;
use App\Http\Controllers\APi\loginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\WorkspacechatController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pusher\PushNotifications\PushNotifications;
use Pusher\Pusher;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Auth::routes();
Route::post('/login', [loginController::class,'index']);
Route::middleware(['auth:api','log.requests'])->group(function(){
    Route::get('/whois', [loginController::class,'whois']);
    Route::post('/add-member',[adminController::class,'addteam']);
    Route::post('/update-member',[adminController::class,'updateteam']);
    Route::post('/update/password',[adminController::class,'updatepassword']);
    Route::get('/get-team',[adminController::class,'getteam']);
    Route::post('/sendNotif',[adminController::class,'sendNotif']);
    Route::post('/add-workspace',[adminController::class,'addworkspace']);
    Route::post('/workspace/update',[adminController::class,'updateworkspace']);
    Route::get('/get-workspace',[adminController::class,'getworkspace']);
    Route::resource('/task', TaskController::class);
    Route::post('/todo', [TodoController::class,'store']);
    Route::get('/todo/{id}', [TodoController::class,'index']);
    Route::post('/todo/update', [TodoController::class,'updates']);
    Route::delete('/todo/{id}', [TodoController::class,'destroy']);
    Route::delete('/workspace/{id}', [adminController::class,'destroyworkspace']);
    Route::delete('/destroymember/{id}', [adminController::class,'destroymember']);
    Route::get('/task/workspace/{id}',[TaskController::class, 'getbyworkspace']);
    Route::get('/task/bytask/{id}',[TaskController::class, 'getbytask']);
    Route::get('/getbyassigment',[TaskController::class, 'getbyassigment']);
    Route::post('/task/{id}',[TaskController::class, 'update']);
    Route::post('/chat/{channel}',[ChatController::class,'store']);
    Route::get('/chat/{id}',[ChatController::class,'index']);
    // workspace chat

    Route::get('/workspace/chat/{id}',[WorkspacechatController::class,'index']);
    Route::post('/workspace/chat/{channel}',[WorkspacechatController::class,'store']);
    // end
});
