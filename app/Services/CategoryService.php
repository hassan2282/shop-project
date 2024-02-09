<?php

namespace App\Services;

use App\Filters\CategoryFilter;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class CategoryService
{
    public function __construct(readonly protected CategoryRepositoryInterface $categoryRepository,
                                readonly protected CategoryFilter              $categoryFilter)
    {
    }

    public function getByFilter()
    {
        $queryParams = [
            'q' => request()->q,
            'status' => request()->status,
        ];
        $columns = [
            'id',
            'description',
            'name',
        ];
        return $this->categoryFilter->getByFilter($queryParams, 15, $columns);
    }

    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }

    public function create($request): RedirectResponse
    {
        $store = $this->categoryRepository->create(attributes: $request->toArray());
        if ($store)
            return to_route('admin.category.index')->with('alert-success', 'دسته بندی شما با موفقیت اضافه شد');
        return to_route('admin.category.index')->with('alert-danger', 'متاسفانه خطایی رخ داده است!');
    }

}
