<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return view('books.index', [
            'books' => Book::all(),
        ]);
    }

    public function show(string $slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        return view('books.show', compact('book'));
    }
}
