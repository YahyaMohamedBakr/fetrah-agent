# مشروع فطرة - توثيق الجلسة

## الهدف
بناء منصة "فطرة" التعليمية: كورسات، استشارات، كتب، مقالات، ووكيل ذكي.

## البيئة
- **Framework**: Laravel 13 + Blade + TailwindCSS
- **Admin Panel**: Filament 5.6
- **LLM**: OpenRouter (Google Gemma 4 31B free)
- **Database**: MySQL
- **Domain**: `fetrah.motaweroon.com`
- **Admin**: `https://fetrah.motaweroon.com/admin`
- **Repository**: `github.com/YahyaMohamedBakr/fetrah-agent`

## Account
- **Admin**: admin@fetrah.com / admin123

---

## تم إنجازه

### البنية الأساسية
- [x] Laravel 13 project + RTL layout (Tajawal, #ff773d)
- [x] Homepage (5 tabs: الوكيل الذكي، الكورسات، الكتب، الاستشارات، المقالات)
- [x] Courses / Books / Articles index pages + detail (show) pages
- [x] Consultations booking page (public form)
- [x] Pages index: `/courses/{slug}`, `/books/{slug}`, `/articles/{slug}`
- [x] صفحات الكل: `/courses`, `/books`, `/articles`, `/consultations`

### الذكاء الاصطناعي
- [x] AIAgentService: OpenRouter integration + mock fallback
- [x] AIAgentController: `POST /api/agent/chat`
- [x] Chat UI (Alpine.js) في home.blade.php
- [x] System prompt مبني على محتوى الموقع (courses, books, articles)
- [x] Model: `google/gemma-4-31b-it:free`

### Admin Dashboard (Filament)
- [x] Filament panel installed (v5.6.7)
- [x] Admin user: admin@fetrah.com / admin123
- [x] CourseResource (CRUD للكورسات)
- [x] BookResource (CRUD للكتب)
- [x] ArticleResource (CRUD للمقالات)
- [x] CategoryResource (CRUD للتصنيفات)
- [x] ConsultationResource (CRUD لطلبات الاستشارات)
- [x] Settings page (اسم الموقع، وصف، تواصل، نص الاستشارات)
- [x] RTL support

### قاعدة البيانات
- [x] Migrations: users, courses, books, articles, categories, consultations, settings
- [x] Wordpress migration command (`migrate:wordpress`) مع دعم JSON dump
- [x] Export command (`export:wordpress`) لتصدير بيانات WordPress
- [x] AdminUserSeeder لإنشاء الأدمن
- [x] `Schema::defaultStringLength(191)` لدعم MariaDB القديم

### البنية التحتية
- [x] .env.example مع إعدادات AI و WordPress
- [x] CSRF exception لـ `/api/agent/chat`
- [x] `defaultStringLength(191)` لمشكلة key length
- [x] تكبير أعمدة `slug` و `title` في المايجريشن (varchar 255/500)
- [x] Fix URL-encoded Arabic slugs في الاستيراد
- [x] .htaccess (public/)

---

## معلّق / يحتاج شغل

### مستعجل
- [ ] **الـ AI agent لسه بيستخدم mock** (مفيش API key شغال على السيرفر؟ احنا بنستخدم `google/gemma-4-31b-it:free` لو الـ key في `.env`)
- [ ] **فورم الاستشارات مش بيحفظ** (لو في error في store)
- [ ] **تحسين مظهر الـ Agent Chat** (رسائل الماركداون مش متنسقة)
- [ ] **صفحات الـ Show** (courses, books, articles) لو في Missing data (بعض slugs لسه فارغة)
- [ ] **تأكيد إن Filament Assets معموللها publish** (`php artisan filament:assets`)
- [ ] **Productuin deployment**:
  - `composer install --no-dev --optimize-autoloader`
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`
  - تشغيل `AdminUserSeeder` ع السيرفر
  - استيراد بيانات WordPress بالـ dump file (أو seed)

### التطويرات المستقبلية
- [ ] **نظام المستخدمين** (تسجيل دخول + اشتراكات)
- [ ] **تفعيل الدفع** (للكورسات المدفوعة)
- [ ] **أداة التركيز** (Focus tool - قفل المواقع)
- [ ] **إحصائيات المتعلم** (لوحة متابعة التقدم)
- [ ] **نظام الدرجات والتقييمات** للكورسات
- [ ] **التكامل مع وسائل التواصل** (بوت واتساب/تلجرام)
- [ ] **SEO** (تحسين meta tags, sitemap, robots.txt)
- [ ] **PWA** (تحويل لتطبيق ويب)

---

## الأوامر المهمة

### السيرفر
```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan filament:assets
```

### استيراد بيانات WordPress
```bash
# على الجهاز المحلي (عندك WordPress DB)
php artisan export:wordpress
# رفع storage/app/wordpress-dump.json للسيرفر

# على السيرفر
php artisan migrate:wordpress --truncate --dump-file=storage/app/wordpress-dump.json
```

### الوكيل الذكي
لو عاوز تغير الموديل:
```
OPENROUTER_MODEL=google/gemma-4-31b-it:free
```

لو عاوز mock (من غير API key) — هشتغل عادي من غير key.

---

## الملاحظات التقنية

- **PHP 8.4+** مطلوب (Filament 5.6 بيشتغل عليه)
- **Mod_rewrite** لازم يكون شغال على السيرفر (عشان routes)
- **MySQL < 5.7.7** محتاج `defaultStringLength(191)` - معمول
- **MariaDB** متوافق (اتجرب)
- مشكلة Filament `Schema vs Form` اتحلت (Filament 5.6 بيستخدم `Schema` مش `Form`)
- مشكلة `navigationIcon` type hint اتحلت (`string|\BackedEnum|null`)
