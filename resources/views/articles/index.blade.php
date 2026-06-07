@extends('layouts.app')

@section('title', 'المقالات - فطرة')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">المقالات</h1>
        <p class="text-gray-500 mt-2">مقالات تثقيفية حول الفطرة والتربية والإيمان</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($articles as $article)
        <div class="bg-white border rounded-xl p-6 hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $article->title }}</h3>
            <p class="text-sm text-gray-500 line-clamp-3 mb-4">{{ $article->excerpt ? strip_tags($article->excerpt) : strip_tags(substr($article->content, 0, 200)) }}</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400">{{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}</span>
                <a href="#" class="text-primary-500 hover:text-primary-600 font-medium">قراءة المزيد ←</a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <span class="text-5xl mb-4 block">📭</span>
            <p class="text-gray-500">لا توجد مقالات حالياً</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
