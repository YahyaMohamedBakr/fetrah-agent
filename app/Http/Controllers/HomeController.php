<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Book;
use App\Models\Course;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'courses' => Course::with('category')->take(6)->get(),
            'books' => Book::take(4)->get(),
            'articles' => Article::take(4)->get(),
        ]);
    }
}
