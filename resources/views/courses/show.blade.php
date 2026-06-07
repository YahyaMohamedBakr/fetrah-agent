@extends('layouts.app')

@section('title', $course->title . ' - فطرة')
@section('meta_description', strip_tags($course->excerpt ?: ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-500">الرئيسية</a>
        <span class="mx-2">/</span>
        <a href="{{ route('courses.index') }}" class="hover:text-primary-500">الكورسات</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $course->title }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        @if($course->thumbnail)
        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-64 object-cover">
        @else
        <div class="w-full h-64 bg-gradient-to-br from-primary-100 to-orange-100 flex items-center justify-center">
            <span class="text-6xl">📚</span>
        </div>
        @endif

        <div class="p-8">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-sm font-medium text-primary-500 bg-primary-50 px-3 py-1 rounded-full">{{ $course->category?->name ?? 'عام' }}</span>
                @if($course->price_type === 'free')
                <span class="text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full">مجاني</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

            @if($course->excerpt)
            <p class="text-lg text-gray-600 mb-6">{{ $course->excerpt }}</p>
            @endif

            <div class="flex items-center gap-6 text-sm text-gray-500 mb-8 pb-8 border-b">
                <span>📚 {{ $course->total_lessons }} درس</span>
                <span>👥 {{ $course->total_students }} طالب</span>
                @if($course->duration_minutes)
                <span>⏱ {{ $course->duration_minutes }} دقيقة</span>
                @endif
            </div>

            @if($course->description)
            <div class="prose prose-gray max-w-none mb-8">
                {!! $course->description !!}
            </div>
            @endif

            @if($course->benefits)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">الفوائد</h2>
                <div class="prose prose-gray max-w-none">
                    {!! $course->benefits !!}
                </div>
            </div>
            @endif

            @if($course->requirements)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">المتطلبات</h2>
                <div class="prose prose-gray max-w-none">
                    {!! $course->requirements !!}
                </div>
            </div>
            @endif

            @if($course->target_audience)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">الفئة المستهدفة</h2>
                <div class="prose prose-gray max-w-none">
                    {!! $course->target_audience !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
