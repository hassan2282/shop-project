<?php

namespace App\Repositories\Category;

use App\Filters\CategoryFilter;
use App\Models\Admin\Category;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function searchData()
    {

    }

    public function getAllCategoriesByFilters()
    {
        $queryParams = [
            'q' => request()->q,
            'status' => request()->status,
        ];
        return (new CategoryFilter($queryParams, 15))->getResult();
    }

    public function create()
    {
        return Category::select(['id', 'name'])->get();
    }

    public function store($request)
    {
        $inputs = $request->all();
        Category::create($inputs);
    }

    public function getAllCategories($category)
    {
        return Category::select(['id', 'name'])->whereNot('id', $category->id)->get();
    }

    public function update($category, $categoryRequest)
    {
        $category->update([
            'name' => $categoryRequest->name,
            'description' => $categoryRequest->description,
            'status' => $categoryRequest->status,
            'parent_id' => $categoryRequest->parent_id,
        ]);
    }

    public function delete($category)
    {
        $category->delete();
    }
}
