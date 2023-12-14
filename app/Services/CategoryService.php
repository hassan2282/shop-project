<?php

namespace App\Services;

class CategoryService
{
    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }
}
