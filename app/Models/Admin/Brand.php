<?php

namespace App\Models\Admin;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['persian_name', 'description', 'status', 'logo', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'persian_name'
            ]
        ];
    }



    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
