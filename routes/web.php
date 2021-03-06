<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Controllers\ChatController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum','verified'])->get('/chat',function(){
    return Inertia::render('Chat/container');
})->name('chat');

Route::group(['prefix'=>'chat','middleware'=>['auth::sanctum']],function(){
    Route::get('/rooms',[ChatController::class,'rooms']);
    Route::get('/room/messages/{roomId}',[ChatController::class,'messages']);
    Route::post('/room/message/{roomId}',[ChatController::class,'newMessage']);
});
