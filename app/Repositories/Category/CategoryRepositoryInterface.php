<?php

namespace App\Repositories\Category;

use App\Repositories\EloquentRepositoryInterface;

interface CategoryRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllCategoriesByFilters();
    public function createData();

//    public function store($request);
//
//    public function getAllCategories($category);
//
//    public function update($category, $categoryRequest);
//
//    public function delete($category);
}
