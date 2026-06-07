@extends('layouts.app')

@section('title', 'الكورسات - فطرة')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">الكورسات</h1>
        <p class="text-gray-500 mt-2">جميع الكورسات المتاحة على منصة فطرة</p>
    </div>

    <div class="flex gap-2 mb-8 flex-wrap">
        <a href="{{ route('courses.index') }}" class="px-4 py-2 bg-primary-500 text-white rounded-full text-sm font-medium">الكل</a>
        @foreach($categories as $category)
        <a href="{{ route('courses.index', ['category' => $category->slug]) }}"
           class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium hover:bg-primary-50 hover:text-primary-600 transition
           {{ request('category') === $category->slug ? 'bg-primary-50 text-primary-600' : '' }}">
            {{ $category->name }}
        </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
            @if($course->thumbnail)
            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-44 object-cover">
            @else
            <div class="w-full h-44 bg-gradient-to-br from-primary-100 to-orange-100 flex items-center justify-center">
                <span class="text-5xl">📚</span>
            </div>
            @endif
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-medium text-primary-500 bg-primary-50 px-2 py-1 rounded-full">{{ $course->category?->name ?? 'عام' }}</span>
                    @if($course->price_type === 'free')
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">مجاني</span>
                    @endif
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $course->excerpt ? strip_tags($course->excerpt) : '' }}</p>
                <div class="flex items-center justify-between pt-3 border-t">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <span>{{ $course->total_lessons }} درس</span>
                    </div>
                    <a href="#" class="text-sm font-medium text-primary-500 hover:text-primary-600">عرض التفاصيل ←</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <span class="text-5xl mb-4 block">📭</span>
            <p class="text-gray-500">لا توجد كورسات في هذا التصنيف حالياً</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
