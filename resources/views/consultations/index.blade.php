@extends('layouts.app')

@section('title', 'الاستشارات - فطرة')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">الاستشارات</h1>
        <p class="text-gray-500 mt-2">احجز استشارة مع متخصصين معتمدين في مختلف المجالات</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">طلب استشارة</h2>

                @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('consultations.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone"
                                   class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">التخصص المطلوب</label>
                        <select name="specialty" required
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none bg-white">
                            <option value="">اختر التخصص</option>
                            <option value="تربوي">تربوي</option>
                            <option value="أسري">أسري</option>
                            <option value="نفسي">نفسي</option>
                            <option value="شرعي">شرعي</option>
                            <option value="تطوير ذاتي">تطوير ذاتي</option>
                            <option value="آخر">آخر</option>
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">التاريخ المفضل</label>
                            <input type="date" name="preferred_date"
                                   class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الوقت المناسب</label>
                            <select name="time_slot"
                                    class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none bg-white">
                                <option value="">اختر الوقت</option>
                                <option value="صباحاً">صباحاً (9-12)</option>
                                <option value="ظهراً">ظهراً (12-3)</option>
                                <option value="مساءً">مساءً (3-6)</option>
                                <option value="ليلاً">ليلاً (6-9)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رسالتك</label>
                        <textarea name="message" rows="4"
                                  class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-500 outline-none"></textarea>
                    </div>

                    <button type="submit"
                            class="px-8 py-3 bg-primary-500 text-white font-medium rounded-xl hover:bg-primary-600 transition shadow-sm">
                        إرسال طلب الاستشارة
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <span class="text-3xl mb-3 block">💬</span>
                <h3 class="font-bold text-gray-900 mb-2">استشارات مخصصة</h3>
                <p class="text-sm text-gray-500">متخصصون معتمدون في المجالات التربوية والأسرية والنفسية</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <span class="text-3xl mb-3 block">🔒</span>
                <h3 class="font-bold text-gray-900 mb-2">خصوصية تامة</h3>
                <p class="text-sm text-gray-500">جميع المعلومات والاستشارات خاصة ومشفرة</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <span class="text-3xl mb-3 block">⏱</span>
                <h3 class="font-bold text-gray-900 mb-2">رد سريع</h3>
                <p class="text-sm text-gray-500">نرد على طلبك في خلال 24 ساعة عمل</p>
            </div>
        </div>
    </div>
</div>
@endsection
