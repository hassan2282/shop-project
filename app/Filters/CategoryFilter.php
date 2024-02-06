<?php

namespace App\Filters;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends BaseFilter
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
