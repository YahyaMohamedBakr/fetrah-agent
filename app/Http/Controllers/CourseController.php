<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $query = Course::with('category');

        if ($slug = request('category')) {
            $category = Category::where('slug', $slug)->first();
            $query->where('category_id', $category?->id);
        }

        return view('courses.index', [
            'courses' => $query->get(),
            'categories' => Category::where('type', 'course')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $course = Course::with('category')->where('slug', $slug)->firstOrFail();

        return view('courses.show', compact('course'));
    }
}
