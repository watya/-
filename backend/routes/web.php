<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImageController;


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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

Route::get('/posts/test', [PostController::class, 'test'])->name('posts.test');

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/home', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/category/{id}', [PostController::class, 'category'])->name('posts.category');
Route::get('/posts/publish', [PostController::class, 'publish'])->name('posts.publish');
Route::DELETE('/images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
Route::POST('/images/store', [ImageController::class, 'store'])->name('images.store');

Route::get('/posts/month', [PostController::class, 'month'])->name('posts.month');

Route::resource('/posts', 'App\Http\Controllers\PostController', ['except' => ['index']]);
Route::resource('/users', 'App\Http\Controllers\UserController');
Route::resource('/comments', 'App\Http\Controllers\CommentController')->middleware('auth'); //ログインしてる人だけ
//Route::resource('/images', 'App\Http\Controllers\ImageController');
