<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php

Route::get('/', [ChatController::class, 'index']);
Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message');
Route::delete('/delete-message/{id}', [ChatController::class, 'deleteMessage']);
Route::get('/chat', [ChatController::class, 'chat']);

