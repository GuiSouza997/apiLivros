<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\LivroController;


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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', [UserController::class, 'details']);
    Route::get('livros', [LivroController::class, 'getAll']);
    Route::post('livros', [LivroController::class, 'create']);
    Route::post('livros/{id}/importar-indices-xml', [LivroController::class, 'importarIndicesXml']);
    // Route::post('book', 'BooksController@create');
    // Route::get('book', 'BooksController@getAll');
    // Route::get('book/{isbn}', 'BooksController@getBookByISBN');
    // Route::post('book', 'BooksController@create');
    // Route::put('book/{id}', 'BooksController@update');
    // Route::delete('book/{id}', 'BooksController@delete');
});