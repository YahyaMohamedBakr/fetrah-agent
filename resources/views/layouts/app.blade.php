<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'فطرة') - منصة الفطرة التعليمية</title>
    <meta name="description" content="@yield('meta_description', 'منصة فطرة - منصة كورسات واستشارات ومستودع كتب ومصادر مدعوم بالذكاء الاصطناعي')">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#ff773d',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .tab-active { border-bottom: 3px solid #ff773d; color: #ff773d; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="فطرة" class="h-10 w-auto">
                        <span class="text-xl font-bold text-gray-900">فطرة</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-500 transition font-medium">الرئيسية</a>
                    <a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-primary-500 transition font-medium">الكورسات</a>
                    <a href="{{ route('books.index') }}" class="text-gray-600 hover:text-primary-500 transition font-medium">الكتب</a>
                    <a href="{{ route('articles.index') }}" class="text-gray-600 hover:text-primary-500 transition font-medium">المقالات</a>
                    <a href="#" class="text-gray-600 hover:text-primary-500 transition font-medium">الاستشارات</a>
                </div>
                <div class="flex items-center gap-3">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-primary-500 border border-primary-500 rounded-lg hover:bg-primary-50 transition">تسجيل الدخول</a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-primary-500 rounded-lg hover:bg-primary-600 transition">إنشاء حساب</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('logo.png') }}" alt="فطرة" class="h-8 w-auto">
                        <span class="text-lg font-bold text-white">فطرة</span>
                    </div>
                    <p class="text-sm">منصة تعليمية متكاملة مدعومة بالذكاء الاصطناعي، تهدف لاستعادة الفطرة السليمة في التعليم والتربية.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">الكورسات</a></li>
                        <li><a href="#" class="hover:text-white transition">الكتب</a></li>
                        <li><a href="#" class="hover:text-white transition">الاستشارات</a></li>
                        <li><a href="#" class="hover:text-white transition">المقالات</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">الدعم</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">اتصل بنا</a></li>
                        <li><a href="#" class="hover:text-white transition">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="hover:text-white transition">سياسة الخصوصية</a></li>
                        <li><a href="#" class="hover:text-white transition">الشروط والأحكام</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2 text-sm">
                        <li>البريد الإلكتروني: info@fetrah.com</li>
                        <li>الهاتف: +966 5X XXX XXXX</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} منصة فطرة</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
