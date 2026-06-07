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
    protected $signature = 'migrate:wordpress {--truncate : Truncate existing tables before import}';

    protected $description = 'Migrate data from WordPress database to Fetrah platform';

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

        $this->info('Starting migration from WordPress...');

        $this->migrateCourseCategories();
        $this->migrateCourses();
        $this->migrateBooks();
        $this->migrateArticles();

        $this->newLine();
        $this->info('Migration completed successfully!');
    }

    protected function migrateCourseCategories(): void
    {
        $this->info('Migrating course categories...');

        $categories = DB::connection('wordpress')
            ->table('terms')
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

        $posts = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'courses')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $meta = DB::connection('wordpress')
                ->table('postmeta')
                ->where('post_id', $post->ID)
                ->pluck('meta_value', 'meta_key');

            $categoryId = null;
            $termRel = DB::connection('wordpress')
                ->table('term_relationships')
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
                $thumbnailPost = DB::connection('wordpress')
                    ->table('posts')
                    ->where('ID', $thumbnailId)
                    ->first();
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

        $posts = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'books')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $meta = DB::connection('wordpress')
                ->table('postmeta')
                ->where('post_id', $post->ID)
                ->pluck('meta_value', 'meta_key');

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

        $posts = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_title', 'LIKE', '%فطرة%')
            ->get();

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            $thumbnailId = get_post_thumbnail_id_wordpress($post->ID);
            $thumbnailUrl = null;
            if ($thumbnailId) {
                $thumbPost = DB::connection('wordpress')
                    ->table('posts')
                    ->where('ID', $thumbnailId)
                    ->first();
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
}

if (!function_exists('get_post_thumbnail_id_wordpress')) {
    function get_post_thumbnail_id_wordpress($postId): ?string
    {
        $meta = DB::connection('wordpress')
            ->table('postmeta')
            ->where('post_id', $postId)
            ->where('meta_key', '_thumbnail_id')
            ->first();
        return $meta?->meta_value;
    }
}
