<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Console\Command;
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

    protected function wpTable(string $table)
    {
        if ($this->dump) {
            return collect($this->dump[$table] ?? []);
        }
        return DB::connection('wordpress')->table($table);
    }

    protected function migrateCourseCategories(): void
    {
        $this->info('Migrating course categories...');

        $categories = $this->wpTable('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'course-category')
            ->select('terms.*', 'term_taxonomy.description')
            ->get();

        $bar = $this->output->createProgressBar(count($categories));
        $bar->start();

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['wp_id' => $cat->term_id],
                [
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'description' => $cat->description ?? '',
                    'type' => 'course',
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($categories) . ' categories.');
    }

    protected function migrateCourses(): void
    {
        $this->info('Migrating courses...');

        $posts = $this->wpTable('posts')
            ->where('post_type', 'courses')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $meta = $this->getPostMeta($post->ID);

            $categoryId = null;
            $termRel = $this->wpTable('term_relationships')
                ->join('term_taxonomy', 'term_relationships.term_taxonomy_id', '=', 'term_taxonomy.term_taxonomy_id')
                ->where('term_relationships.object_id', $post->ID)
                ->where('term_taxonomy.taxonomy', 'course-category')
                ->first();

            if ($termRel) {
                $category = Category::where('wp_id', $termRel->term_id)->first();
                $categoryId = $category?->id;
            }

            $thumbnailId = $meta['_thumbnail_id'] ?? null;
            $thumbnailUrl = null;
            if ($thumbnailId) {
                $thumbnailPost = collect($this->dump ? $this->dump['posts'] : null)
                    ->firstWhere('ID', $thumbnailId);

                if (!$thumbnailPost) {
                    $thumbnailPost = $this->wpTable('posts')->where('ID', $thumbnailId)->first();
                }
                $thumbnailUrl = $thumbnailPost?->guid;
            }

            Course::updateOrCreate(
                ['wp_id' => $post->ID],
                [
                    'title' => html_entity_decode($post->post_title),
                    'slug' => $post->post_name ?: Str::slug($post->post_title),
                    'description' => $post->post_content,
                    'excerpt' => strip_tags($post->post_excerpt ?: ''),
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
        $this->info('Migrated ' . count($posts) . ' courses.');
    }

    protected function migrateBooks(): void
    {
        $this->info('Migrating books...');

        $posts = $this->wpTable('posts')
            ->where('post_type', 'books')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $meta = $this->getPostMeta($post->ID);

            Book::updateOrCreate(
                ['wp_id' => $post->ID],
                [
                    'title' => html_entity_decode($post->post_title),
                    'slug' => $post->post_name ?: Str::slug($post->post_title),
                    'description' => $post->post_content,
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
        $this->info('Migrated ' . count($posts) . ' books.');
    }

    protected function migrateArticles(): void
    {
        $this->info('Migrating articles...');

        $posts = $this->wpTable('posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_title', 'LIKE', '%' . $this->articleTitleFilter . '%')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $thumbnailUrl = null;
            $thumbnailId = $this->getPostMetaValue($post->ID, '_thumbnail_id');

            if ($thumbnailId) {
                $thumbPost = collect($this->dump ? $this->dump['posts'] : null)
                    ->firstWhere('ID', $thumbnailId);

                if (!$thumbPost && !$this->dump) {
                    $thumbPost = $this->wpTable('posts')->where('ID', $thumbnailId)->first();
                }
                $thumbnailUrl = $thumbPost?->guid;
            }

            Article::updateOrCreate(
                ['wp_id' => $post->ID],
                [
                    'title' => html_entity_decode($post->post_title),
                    'slug' => $post->post_name ?: Str::slug($post->post_title),
                    'content' => $post->post_content,
                    'excerpt' => strip_tags($post->post_excerpt ?: ''),
                    'featured_image' => $thumbnailUrl,
                    'is_published' => true,
                    'published_at' => $post->post_date,
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($posts) . ' articles.');
    }

    private function getPostMeta($postId): array
    {
        if ($this->dump) {
            $rows = collect($this->dump['postmeta'] ?? [])
                ->where('post_id', $postId);
            return $rows->pluck('meta_value', 'meta_key')->toArray();
        }

        return DB::connection('wordpress')
            ->table('postmeta')
            ->where('post_id', $postId)
            ->pluck('meta_value', 'meta_key')
            ->toArray();
    }

    private function getPostMetaValue($postId, string $key): ?string
    {
        $meta = $this->getPostMeta($postId);
        return $meta[$key] ?? null;
    }
}
