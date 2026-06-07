<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Book;
use App\Models\Course;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private array $context;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key') ?? config('services.ai.api_key') ?? '';
        $this->model = config('services.openrouter.model', 'google/gemini-2.0-flash-001');
        $this->baseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1');
        $this->context = $this->buildContext();
    }

    public function chat(string $message, array $history = []): array
    {
        $systemPrompt = $this->buildSystemPrompt();
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        if (empty($this->apiKey)) {
            return $this->mockResponse($message);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'http://fetrah-agent.test'),
                'X-Title' => 'فطرة - AI Agent',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'message' => $data['choices'][0]['message']['content'],
                    'role' => 'assistant',
                ];
            }

            Log::error('AI Agent API error: ' . $response->body());
            return [
                'message' => 'عذراً، حدث خطأ في التواصل مع الوكيل الذكي. الرجاء المحاولة لاحقاً.',
                'role' => 'assistant',
            ];
        } catch (\Exception $e) {
            Log::error('AI Agent exception: ' . $e->getMessage());
            return [
                'message' => 'عذراً، حدث خطأ في التواصل مع الوكيل الذكي.',
                'role' => 'assistant',
            ];
        }
    }

    private function buildSystemPrompt(): string
    {
        $courses = Course::with('category')->get();
        $books = Book::all();
        $articles = Article::all();

        $coursesInfo = $courses->map(fn($c) => "- {$c->title} (التصنيف: {$c->category?->name}) - {$c->excerpt}")->join("\n");
        $booksInfo = $books->map(fn($b) => "- {$b->title} - {$b->author}")->join("\n");
        $articlesInfo = $articles->map(fn($a) => "- {$a->title}")->join("\n");

        return <<<PROMPT
أنت الوكيل الذكي لمنصة "فطرة" التعليمية. اسمك "فطرة".

مهمتك:
- التحدث مع المستخدمين بطريقة ودية ومحترمة كأنك إنسان
- فهم احتياجات المستخدم وتوجيهه للمحتوى المناسب في المنصة
- التوصية بالكورسات، الكتب، والمقالات المتاحة
- الرد باللغة العربية الفصحى المبسطة

المحتوى المتاح في المنصة:

الكورسات:
{$coursesInfo}

الكتب:
{$booksInfo}

المقالات:
{$articlesInfo}

إرشادات:
- كن ودوداً ومشجعاً
- استخدم لغة عربية سليمة وبسيطة
- اسأل المستخدم عن احتياجاته لتوجيهه بشكل أفضل
- إذا سأل عن شيء خارج نطاق المنصة، ساعده بقدر معرفتك
- ذكر المستخدم أن الاستشارات المباشرة مع المتخصصين ستكون متاحة قريباً

تذكر: أنت هنا لمساعدة المستخدم في رحلته التعليمية والتربوية على منهج الفطرة.
PROMPT;
    }

    private function buildContext(): array
    {
        return [
            'courses' => Course::with('category')->get()->toArray(),
            'books' => Book::all()->toArray(),
            'articles' => Article::all()->toArray(),
        ];
    }

    private function mockResponse(string $message): array
    {
        $message = mb_strtolower($message);
        $response = '';

        $courses = Course::with('category')->get();
        $books = Book::all();

        if (str_contains($message, 'رجولة') || str_contains($message, 'رجول')) {
            $course = $courses->firstWhere('title', 'برنامج الطريق إلى الرجولة');
            $response = "لدينا دورة ممتازة عن الرجولة: **{$course?->title}**\n{$course?->excerpt}\n\nيمكنك البدء فيها الآن من تبويب الكورسات.";
        } elseif (str_contains($message, 'أنوث') || str_contains($message, 'انوث')) {
            $course = $courses->firstWhere('title', 'برنامج الأنوثة كنزي الداخلي');
            $response = "نعم! لدينا **{$course?->title}** وهي دورة رائعة تكتشف فيها المرأة جوهر أنوثتها.\n\n{$course?->excerpt}";
        } elseif (str_contains($message, 'زواج') || str_contains($message, 'فتيات') || str_contains($message, 'تأهيل')) {
            $course = $courses->firstWhere('title', 'تأهيل الفتيات للزواج');
            $response = "نعم! دورة **{$course?->title}** متاحة مجاناً.\n{$course?->excerpt}";
        } elseif (str_contains($message, 'علاقات') || str_contains($message, 'مشاعر') || str_contains($message, 'زوج')) {
            $course = $courses->firstWhere('title', 'إدارة العلاقات والمشاعر للزوجة');
            $response = "دورة ممتازة: **{$course?->title}**\n{$course?->excerpt}";
        } elseif (str_contains($message, 'فطرة') || str_contains($message, 'مدخل') || str_contains($message, 'إيمان')) {
            $response = "لدينا عدة دورات عن الفطرة:\n";
            foreach ($courses as $c) {
                if (str_contains($c->title, 'فطرة') || str_contains($c->title, 'إيمان')) {
                    $response .= "- **{$c->title}**: {$c->excerpt}\n";
                }
            }
        } elseif (str_contains($message, 'تربية') || str_contains($message, 'أبناء') || str_contains($message, 'مربي') || str_contains($message, 'ابناء')) {
            $course = $courses->firstWhere('title', 'التربية على نهج الفطرة');
            $response = "نعم! **{$course?->title}** متاحة.\n{$course?->excerpt}";
        } elseif (str_contains($message, 'كتاب') || str_contains($message, 'كتب')) {
            if ($books->isNotEmpty()) {
                $book = $books->first();
                $response = "لدينا كتاب **{$book->title}** للمؤلف {$book->author}. يمكنك تحميله من تبويب الكتب.";
            } else {
                $response = "قريباً ستتوفر المزيد من الكتب في مكتبة فطرة.";
            }
        } elseif (str_contains($message, 'استشار') || str_contains($message, 'متخصص')) {
            $response = "خدمة الاستشارات مع متخصصين معتمدين ستكون متاحة قريباً على المنصة. يمكنك حالياً الاستفادة من الكورسات والكتب المتاحة.";
        } elseif (str_contains($message, 'مرحبا') || str_contains($message, 'السلام') || str_contains($message, 'hi') || str_contains($message, 'hello')) {
            $response = "وعليكم السلام ورحمة الله وبركاته! 👋\n\nأنا وكيل فطرة الذكي، كيف يمكنني مساعدتك اليوم؟ يمكنك سؤالي عن الكورسات، الكتب، أو أي استفسار تربوي أو تعليمي.";
        } else {
            $response = "شكراً لسؤالك! 😊\n\nيمكنني مساعدتك في:\n- اقتراح كورس مناسب لك\n- التوصية بكتاب\n- الإجابة عن استفساراتك التربوية والإيمانية\n\nاختر ما يناسبك من الأقسام أعلاه أو أخبرني ماذا تريد؟";
        }

        return [
            'message' => $response,
            'role' => 'assistant',
        ];
    }
}
