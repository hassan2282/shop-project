<?php

namespace App\Models\Admin;

use App\Models\Media;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'description', 'status', 'slug', 'price', 'sold_number', 'frozen_number', 'category_id', 'brand_id'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function attribute_values(): HasMany
    {
        return $this->HasMany(AttributeValue::class);
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
