<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

Route::post('/api/agent/chat', [App\Http\Controllers\API\AIAgentController::class, 'chat']);
