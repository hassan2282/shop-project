<?php

namespace App\Models\Admin;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'position', 'status'];

    public function media()
    {
        return $this->morphOne(Media::class,'mediable');
    }
}
