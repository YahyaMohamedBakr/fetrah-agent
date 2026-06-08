<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('author')
                    ->default(null),
                TextInput::make('publisher')
                    ->default(null),
                TextInput::make('file_path')
                    ->default(null),
                FileUpload::make('cover_image')
                    ->image(),
                TextInput::make('isbn')
                    ->default(null),
                TextInput::make('pages')
                    ->numeric()
                    ->default(null),
                TextInput::make('wp_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('category_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
