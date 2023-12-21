<?php

namespace App\Services;

use App\Models\Admin\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;
    public function __construct()
    {
        $this->categoryRepository = app()->make(CategoryRepository::class);
    }

    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }

    public function store($request)
    {
        return $this->categoryRepository->store($request);
    }
}
