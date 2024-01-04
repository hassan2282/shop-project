<?php

namespace App\Models\Admin;

use App\Models\Media;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'original_name'
            ]
        ];
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
