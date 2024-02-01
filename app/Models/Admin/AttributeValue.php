<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeValue extends Model
{
    use HasFactory;
    protected $table = 'attribute_values';
    protected $fillable = ['value'];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }
}
