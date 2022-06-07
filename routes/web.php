<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

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

Route::get('/', [PagesController::class, 'index']);

Route::resource('/blog', PostsController::class);
Route::resource('/comments', CommentsController::class);
Route::post('comments/reply',[CommentsController::class, 'reply']);
Route::get('blog/thumbs_up/{id}',[PostsController::class, 'thumbs_up']);
Route::get('blog/thumbs_down/{id}',[PostsController::class, 'thumbs_down']);

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

