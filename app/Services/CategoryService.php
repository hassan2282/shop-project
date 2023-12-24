<?php

namespace App\Services;

use App\Models\Admin\Category;
use App\Repositories\BaseRepository;

class CategoryService extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }
}
