<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'unit','product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function value()
    {
        return $this->hasOne(AttributeValue::class);
    }
}
