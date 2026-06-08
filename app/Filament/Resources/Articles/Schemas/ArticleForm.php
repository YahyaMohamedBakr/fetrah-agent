<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('content')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('excerpt')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('author')
                    ->default(null),
                FileUpload::make('featured_image')
                    ->image(),
                TextInput::make('wp_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('category_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_published')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
