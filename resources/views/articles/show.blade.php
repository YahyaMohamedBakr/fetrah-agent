@extends('layouts.app')

@section('title', $article->title . ' - فطرة')
@section('meta_description', strip_tags($article->excerpt ?: ''))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-500">الرئيسية</a>
        <span class="mx-2">/</span>
        <a href="{{ route('articles.index') }}" class="hover:text-primary-500">المقالات</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $article->title }}</span>
    </nav>

    <article class="bg-white rounded-2xl shadow-sm border p-8">
        @if($article->featured_image)
        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-64 object-cover rounded-xl mb-8">
        @endif

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

        <div class="text-sm text-gray-400 mb-8 pb-6 border-b">
            @if($article->author)
            <span>✍️ {{ $article->author }}</span>
            <span class="mx-3">|</span>
            @endif
            <span>📅 {{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}</span>
        </div>

        <div class="prose prose-gray max-w-none leading-relaxed text-lg">
            {!! $article->content !!}
        </div>
    </article>
</div>
@endsection
