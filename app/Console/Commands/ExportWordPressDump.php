<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportWordPressDump extends Command
{
    protected $signature = 'export:wordpress {path? : Output file path}';

    protected $description = 'Export WordPress data to a JSON dump file for server migration';

    public function handle(): void
    {
        $path = $this->argument('path') ?? storage_path('app/wordpress-dump.json');

        $this->info('Exporting WordPress data...');

        $data = [];

        $data['categories'] = DB::connection('wordpress')
            ->table('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'course-category')
            ->select('terms.*', 'term_taxonomy.description')
            ->get()
            ->toArray();

        $this->info('Found ' . count($data['categories']) . ' categories.');

        $posts = DB::connection('wordpress')
            ->table('posts')
            ->whereIn('post_type', ['courses', 'books', 'post'])
            ->get();

        $data['posts'] = [];
        $data['postmeta'] = [];

        foreach ($posts as $post) {
            $data['posts'][] = $post;

            $meta = DB::connection('wordpress')
                ->table('postmeta')
                ->where('post_id', $post->ID)
                ->select('meta_id', 'meta_key', 'meta_value')
                ->get()
                ->toArray();

            foreach ($meta as $m) {
                $data['postmeta'][] = $m;
            }
        }

        $data['term_relationships'] = DB::connection('wordpress')
            ->table('term_relationships')
            ->get()
            ->toArray();

        $this->info('Found ' . count($data['posts']) . ' posts.');
        $this->info('Found ' . count($data['postmeta']) . ' meta entries.');
        $this->info('Found ' . count($data['term_relationships']) . ' term relationships.');

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Export saved to: {$path}");
        $this->warn('File size: ' . round(filesize($path) / 1024, 2) . ' KB');
    }
}
