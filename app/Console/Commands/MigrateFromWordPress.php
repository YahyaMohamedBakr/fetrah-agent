<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateFromWordPress extends Command
{
    protected $signature = 'migrate:wordpress
        {--truncate : Truncate existing tables before import}
        {--dump-file= : Path to JSON dump file (instead of DB connection)}';

    protected $description = 'Migrate data from WordPress to Fetrah platform';

    private ?array $dump = null;
    private string $articleTitleFilter = 'فطرة';

    public function handle(): void
    {
        if ($this->option('truncate')) {
            $this->warn('Truncating existing tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Course::truncate();
            Book::truncate();
            Article::truncate();
            Category::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $dumpFile = $this->option('dump-file');
        if ($dumpFile) {
            if (!file_exists($dumpFile)) {
                $this->error("Dump file not found: {$dumpFile}");
                return;
            }
            $this->dump = json_decode(file_get_contents($dumpFile), true);
            if (!$this->dump) {
                $this->error('Invalid JSON dump file.');
                return;
            }
            $this->info('Loaded dump from: ' . $dumpFile);
        }

        $this->info('Starting migration from WordPress...');

        $this->migrateCourseCategories();
        $this->migrateCourses();
        $this->migrateBooks();
        $this->migrateArticles();

        $this->newLine();
        $this->info('Migration completed successfully!');
    }

    private function collect(string $table): Collection
    {
        if ($this->dump) {
            return collect($this->dump[$table] ?? []);
        }
        return collect(DB::connection('wordpress')->table($table)->get()->map(fn($r) => (array) $r));
    }

    private function first(string $table, array $conditions): ?array
    {
        $rows = $this->collect($table);
        foreach ($conditions as $key => $value) {
            $rows = $rows->where($key, $value);
        }
        return $rows->first();
    }

    private function pluck(string $table, string $valueCol, string $keyCol, array $conditions = []): array
    {
        $rows = $this->collect($table);
        foreach ($conditions as $key => $value) {
            $rows = $rows->where($key, $value);
        }
        return $rows->pluck($valueCol, $keyCol)->toArray();
    }

    private function getPostMeta(int $postId): array
    {
        return $this->pluck('postmeta', 'meta_value', 'meta_key', ['post_id' => $postId]);
    }

    private function makeSlug(array $post): string
    {
        $slug = $post['post_name'] ?? '';
        if (!$slug) {
            return Str::slug($post['post_title']);
        }
        if (str_contains($slug, '%')) {
            $slug = urldecode($slug);
        }
        $slug = Str::slug($slug);
        return $slug ?: 'post-' . $post['ID'];
    }

    protected function migrateCourseCategories(): void
    {
        $this->info('Migrating course categories...');

        $terms = $this->collect('terms');
        $taxonomies = $this->collect('term_taxonomy');

        $categories = $terms->filter(function ($term) use ($taxonomies) {
            $tax = $taxonomies->firstWhere('term_id', $term['term_id']);
            return $tax && ($tax['taxonomy'] ?? '') === 'course-category';
        })->map(function ($term) use ($taxonomies) {
            $tax = $taxonomies->firstWhere('term_id', $term['term_id']);
            return [
                'term_id' => $term['term_id'],
                'name' => $term['name'],
                'slug' => $term['slug'],
                'description' => $tax['description'] ?? '',
            ];
        });

        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['wp_id' => $cat['term_id']],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'] ?? '',
                    'type' => 'course',
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . $categories->count() . ' categories.');
    }

    protected function migrateCourses(): void
    {
        $this->info('Migrating courses...');

        $posts = $this->collect('posts')
            ->where('post_type', 'courses')
            ->where('post_status', 'publish');

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            $meta = $this->getPostMeta($post['ID']);

            $categoryId = null;
            $termRel = $this->first('term_relationships', ['object_id' => $post['ID']]);

            if ($termRel) {
                $tax = $this->first('term_taxonomy', [
                    'term_taxonomy_id' => $termRel['term_taxonomy_id'],
                    'taxonomy' => 'course-category',
                ]);

                if ($tax) {
                    $category = Category::where('wp_id', $tax['term_id'])->first();
                    $categoryId = $category?->id;
                }
            }

            $thumbnailId = $meta['_thumbnail_id'] ?? null;
            $thumbnailUrl = null;
            if ($thumbnailId) {
                $thumbPost = $this->first('posts', ['ID' => (int) $thumbnailId]);
                $thumbnailUrl = $thumbPost['guid'] ?? null;
            }

            Course::updateOrCreate(
                ['wp_id' => $post['ID']],
                [
                    'title' => html_entity_decode($post['post_title']),
                    'slug' => Str::limit($this->makeSlug($post), 245, ''),
                    'description' => $post['post_content'],
                    'excerpt' => strip_tags($post['post_excerpt'] ?: ''),
                    'benefits' => $meta['_tutor_course_benefits'] ?? null,
                    'target_audience' => $meta['_tutor_course_target_audience'] ?? null,
                    'requirements' => $meta['_tutor_course_requirements'] ?? null,
                    'price_type' => $meta['_tutor_course_price_type'] ?? 'free',
                    'price' => $meta['tutor_course_price'] ?? null,
                    'sale_price' => $meta['tutor_course_sale_price'] ?? null,
                    'category_id' => $categoryId,
                    'thumbnail' => $thumbnailUrl,
                    'is_published' => true,
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . $posts->count() . ' courses.');
    }

    protected function migrateBooks(): void
    {
        $this->info('Migrating books...');

        $posts = $this->collect('posts')
            ->where('post_type', 'books')
            ->where('post_status', 'publish');

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            $meta = $this->getPostMeta($post['ID']);

            Book::updateOrCreate(
                ['wp_id' => $post['ID']],
                [
                    'title' => html_entity_decode($post['post_title']),
                    'slug' => Str::limit($this->makeSlug($post), 245, ''),
                    'description' => $post['post_content'],
                    'author' => $meta['wbg_author'] ?? null,
                    'publisher' => $meta['wbg_publisher'] ?? null,
                    'file_path' => $meta['wbg_download_link'] ?? null,
                    'is_published' => ($meta['wbg_status'] ?? 'active') === 'active',
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . $posts->count() . ' books.');
    }

    protected function migrateArticles(): void
    {
        $this->info('Migrating articles...');

        $posts = $this->collect('posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->filter(fn($p) => str_contains($p['post_title'] ?? '', $this->articleTitleFilter));

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            $meta = $this->getPostMeta($post['ID']);

            $thumbnailUrl = null;
            $thumbnailId = $meta['_thumbnail_id'] ?? null;
            if ($thumbnailId) {
                $thumbPost = $this->first('posts', ['ID' => (int) $thumbnailId]);
                $thumbnailUrl = $thumbPost['guid'] ?? null;
            }

            Article::updateOrCreate(
                ['wp_id' => $post['ID']],
                [
                    'title' => html_entity_decode($post['post_title']),
                    'slug' => Str::limit($this->makeSlug($post), 245, ''),
                    'content' => $post['post_content'],
                    'excerpt' => strip_tags($post['post_excerpt'] ?: ''),
                    'featured_image' => $thumbnailUrl,
                    'is_published' => true,
                    'published_at' => $post['post_date'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . $posts->count() . ' articles.');
    }
}
