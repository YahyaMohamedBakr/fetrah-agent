<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
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
                Textarea::make('excerpt')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('benefits')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('target_audience')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('requirements')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('price_type')
                    ->required()
                    ->default('free'),
                TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('sale_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('category_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('thumbnail')
                    ->default(null),
                TextInput::make('level')
                    ->default(null),
                TextInput::make('duration_minutes')
                    ->numeric()
                    ->default(null),
                TextInput::make('wp_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_published')
                    ->required(),
                TextInput::make('total_lessons')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_students')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('average_rating')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
