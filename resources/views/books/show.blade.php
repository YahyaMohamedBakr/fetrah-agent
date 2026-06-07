@extends('layouts.app')

@section('title', $book->title . ' - فطرة')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-500">الرئيسية</a>
        <span class="mx-2">/</span>
        <a href="{{ route('books.index') }}" class="hover:text-primary-500">الكتب</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $book->title }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="md:flex">
            <div class="md:w-80 h-80 bg-gradient-to-br from-gray-50 to-primary-50 flex items-center justify-center">
                <span class="text-8xl">📖</span>
            </div>
            <div class="p-8 flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $book->title }}</h1>

                @if($book->author)
                <p class="text-lg text-gray-600 mb-2">تأليف: {{ $book->author }}</p>
                @endif

                @if($book->publisher)
                <p class="text-gray-500 mb-4">الناشر: {{ $book->publisher }}</p>
                @endif

                @if($book->isbn)
                <p class="text-sm text-gray-400 mb-4">ISBN: {{ $book->isbn }}</p>
                @endif

                @if($book->pages)
                <p class="text-sm text-gray-400 mb-6">عدد الصفحات: {{ $book->pages }}</p>
                @endif

                @if($book->file_path)
                <a href="{{ $book->file_path }}" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white font-medium rounded-xl hover:bg-primary-600 transition shadow-sm">
                    📥 تحميل الكتاب
                </a>
                @endif
            </div>
        </div>

        @if($book->description)
        <div class="p-8 pt-0">
            <h2 class="text-xl font-bold text-gray-900 mb-4">عن الكتاب</h2>
            <div class="prose prose-gray max-w-none">
                {!! $book->description !!}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
