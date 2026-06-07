<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'benefits',
        'target_audience',
        'requirements',
        'price_type',
        'price',
        'sale_price',
        'category_id',
        'thumbnail',
        'level',
        'duration_minutes',
        'wp_id',
        'is_published',
        'total_lessons',
        'total_students',
        'average_rating',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'average_rating' => 'decimal:1',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
