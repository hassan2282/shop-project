<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface
{
    public function searchData();

    public function getAllCategoriesByFilters();
    public function create();

    public function store($request);

    public function getAllCategories($category);

    public function update($category, $categoryRequest);

    public function delete($category);
}
