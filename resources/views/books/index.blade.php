@extends('layouts.app')

@section('title', 'الكتب - فطرة')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">الكتب</h1>
        <p class="text-gray-500 mt-2">مستودع كتب فطرة</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($books as $book)
        <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition group">
            <div class="h-56 bg-gradient-to-br from-gray-50 to-primary-50 flex items-center justify-center relative">
                <span class="text-6xl">📖</span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-primary-500 transition">{{ $book->title }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ $book->author }}</p>
                @if($book->publisher)
                <p class="text-xs text-gray-400 mb-3">{{ $book->publisher }}</p>
                @endif
                @if($book->file_path)
                <a href="{{ $book->file_path }}" target="_blank"
                   class="inline-flex items-center gap-1 text-sm font-medium text-primary-500 hover:text-primary-600">
                    <span>📥</span> تحميل الكتاب
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <span class="text-5xl mb-4 block">📭</span>
            <p class="text-gray-500">لا توجد كتب متاحة حالياً</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
