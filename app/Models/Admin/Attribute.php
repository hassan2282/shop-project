<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'unit','product_id'];

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }


    public function attribute_values(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class);
    }
}
