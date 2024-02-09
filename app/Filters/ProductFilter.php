<?php

namespace App\Filters;

use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;

class ProductFilter extends BaseFilter
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }
}
