@extends('layouts.app')

@section('title', 'فطرة - منصة التعليم بالفطرة')

@section('content')
<section class="relative bg-gradient-to-br from-primary-50 via-white to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-4">
                مرحباً بك في <span class="text-primary-500">فطرة</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                منصة تعليمية ذكية تعيدك إلى فطرتك السليمة.. كورسات، استشارات، كتب، ومصادر مدعومة بالذكاء الاصطناعي
            </p>
            <div class="flex justify-center gap-4">
                <a href="#tabs-section" class="px-8 py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition shadow-lg shadow-primary-200">
                    ابدأ رحلتك
                </a>
                <a href="{{ route('courses.index') }}" class="px-8 py-3 bg-white text-primary-500 font-semibold rounded-xl border border-primary-300 hover:bg-primary-50 transition">
                    تصفح الكورسات
                </a>
            </div>
        </div>
    </div>
</section>

<section id="tabs-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-sm border">
        <div class="border-b" x-data="{ activeTab: 'agent' }">
            <div class="flex overflow-x-auto">
                <button @click="activeTab = 'agent'" :class="activeTab === 'agent' ? 'tab-active' : 'text-gray-500'" class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 border-transparent hover:text-primary-500 transition">
                    <span class="ml-2">🤖</span> الوكيل الذكي
                </button>
                <button @click="activeTab = 'courses'" :class="activeTab === 'courses' ? 'tab-active' : 'text-gray-500'" class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 border-transparent hover:text-primary-500 transition">
                    <span class="ml-2">📚</span> الكورسات
                </button>
                <button @click="activeTab = 'books'" :class="activeTab === 'books' ? 'tab-active' : 'text-gray-500'" class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 border-transparent hover:text-primary-500 transition">
                    <span class="ml-2">📖</span> الكتب
                </button>
                <button @click="activeTab = 'consultations'" :class="activeTab === 'consultations' ? 'tab-active' : 'text-gray-500'" class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 border-transparent hover:text-primary-500 transition">
                    <span class="ml-2">💬</span> الاستشارات
                </button>
                <button @click="activeTab = 'articles'" :class="activeTab === 'articles' ? 'tab-active' : 'text-gray-500'" class="px-6 py-4 font-medium text-sm whitespace-nowrap border-b-2 border-transparent hover:text-primary-500 transition">
                    <span class="ml-2">📝</span> المقالات
                </button>
            </div>

            <div class="p-6">
                <div x-show="activeTab === 'agent'" x-cloak>
                    <div class="text-center py-8">
                        <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-4xl">🤖</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">الوكيل الذكي فطرة</h2>
                        <p class="text-gray-600 max-w-xl mx-auto mb-8">
                            اسألني عن أي شيء! أنا هنا لأرشدك للكورس المناسب، أو أوصي بكتاب، أو أجاوب على استفساراتك
                        </p>
                        <div class="max-w-2xl mx-auto">
                            <div class="bg-gray-50 rounded-2xl p-6 border">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                                        <span>🤖</span>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl rounded-tr-sm shadow-sm">
                                        <p class="text-gray-700">مرحباً! أنا وكيل فطرة الذكي. كيف يمكنني مساعدتك اليوم؟</p>
                                        <p class="text-xs text-gray-400 mt-2">يمكنك سؤالي بصوت أو كتابة</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <input type="text" placeholder="اكتب سؤالك هنا..." class="flex-1 px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none">
                                    <button class="px-6 py-3 bg-primary-500 text-white font-medium rounded-xl hover:bg-primary-600 transition shadow-sm">
                                        أرسل
                                    </button>
                                    <button class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition" title="تسجيل صوتي">
                                        🎤
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-center gap-2 mt-6">
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 text-sm rounded-full">أريد تطوير ذاتي</span>
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 text-sm rounded-full">أنصحني بكتاب</span>
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 text-sm rounded-full">كيف أربي أبنائي؟</span>
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 text-sm rounded-full">مشكلة في العلاقات الزوجية</span>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'courses'" x-cloak>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">جميع الكورسات</h2>
                        <a href="{{ route('courses.index') }}" class="text-primary-500 hover:text-primary-600 font-medium text-sm">عرض الكل ←</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                        <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                            @if($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
                            @else
                            <div class="w-full h-40 bg-gradient-to-br from-primary-100 to-orange-100 flex items-center justify-center">
                                <span class="text-4xl">📚</span>
                            </div>
                            @endif
                            <div class="p-4">
                                <span class="text-xs font-medium text-primary-500 bg-primary-50 px-2 py-1 rounded-full">{{ $course->category?->name ?? 'عام' }}</span>
                                <h3 class="font-semibold text-gray-900 mt-2 mb-1">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $course->excerpt ? strip_tags($course->excerpt) : '' }}</p>
                                <div class="flex items-center justify-between mt-4">
                                    <span class="text-sm text-gray-400">
                                        @if($course->price_type === 'free')
                                        مجاني
                                        @else
                                        {{ $course->price }} ريال
                                        @endif
                                    </span>
                                    <a href="#" class="text-sm font-medium text-primary-500 hover:text-primary-600">عرض التفاصيل ←</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="activeTab === 'books'" x-cloak>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">الكتب</h2>
                        <a href="{{ route('books.index') }}" class="text-primary-500 hover:text-primary-600 font-medium text-sm">عرض الكل ←</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($books as $book)
                        <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition">
                            <div class="h-48 bg-gradient-to-br from-gray-50 to-primary-50 flex items-center justify-center">
                                <span class="text-5xl">📖</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $book->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $book->author }}</p>
                                @if($book->file_path)
                                <a href="{{ $book->file_path }}" target="_blank" class="mt-3 inline-block text-sm font-medium text-primary-500 hover:text-primary-600">
                                    تحميل ←
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="activeTab === 'consultations'" x-cloak>
                    <div class="text-center py-12">
                        <span class="text-5xl mb-4 block">💬</span>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">الاستشارات</h2>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">قريباً.. خدمة الاستشارات مع متخصصين معتمدين في مختلف المجالات</p>
                        <div class="w-16 h-1 bg-primary-300 mx-auto rounded-full"></div>
                    </div>
                </div>

                <div x-show="activeTab === 'articles'" x-cloak>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">المقالات</h2>
                        <a href="{{ route('articles.index') }}" class="text-primary-500 hover:text-primary-600 font-medium text-sm">عرض الكل ←</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($articles as $article)
                        <div class="bg-white border rounded-xl p-5 hover:shadow-md transition">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $article->excerpt ? strip_tags($article->excerpt) : '' }}</p>
                            <div class="flex items-center justify-between mt-4 text-sm">
                                <span class="text-gray-400">{{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}</span>
                                <a href="#" class="text-primary-500 hover:text-primary-600 font-medium">قراءة ←</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border text-center">
            <span class="text-4xl mb-4 block">🎯</span>
            <h3 class="text-lg font-bold text-gray-900 mb-2">مواد تثقيفية</h3>
            <p class="text-gray-500 text-sm">مكتبة ضخمة من الكورسات والكتب والمقالات المصممة خصيصاً لتنمية وعيك وفطرتك</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border text-center">
            <span class="text-4xl mb-4 block">📊</span>
            <h3 class="text-lg font-bold text-gray-900 mb-2">إحصائيات المتابعة الذاتية</h3>
            <p class="text-gray-500 text-sm">تابع تقدمك التعليمي، وقت تعلمك، وإنجازاتك الشخصية من خلال لوحة إحصائيات متكاملة</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border text-center">
            <span class="text-4xl mb-4 block">🧘</span>
            <h3 class="text-lg font-bold text-gray-900 mb-2">أداة التركيز</h3>
            <p class="text-gray-500 text-sm">قريباً.. أداة ذكية تقفل الإشعارات وتمنع السوشيال ميديا لفترات محددة لتعزيز إنتاجيتك</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
