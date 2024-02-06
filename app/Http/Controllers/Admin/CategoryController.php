<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CategoryStoreRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Services\CategoryService;


class CategoryController extends Controller
{
    public function __construct(CategoryRepositoryInterface $categoryRepository,
                                CategoryService             $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getByFilter();
        if (is_null($categories)) {
            $categories = $this->categoryRepository->allWithPaginate();
        }
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->all();
        return view('admin.category.create', compact('categories'));
    }

    public function store(CategoryStoreRequest $request)
    {
        return $this->categoryService->create($request);
    }

    public function edit(Category $category)
    {
        $allCategories = $this->categoryRepository->all();
        return view('admin.category.edit', compact('category', 'allCategories'));
    }

    public function update(CategoryUpdateRequest $request,int $id)
    {
        $update = $this->categoryRepository->update($request->toArray(), $id);
        if ($update)
        return to_route('admin.category.index')->with('alert-success', 'دسته بندی شما با موفقیت ویرایش شد');
        return to_route('admin.category.index')->with('alert-danger', 'متاسفانه خطایی رخ داده است');
    }

    public function delete(int $id)
    {
        $this->categoryRepository->delete($id);
        return back()->with('alert-success', 'دسته بندی شما با موفقیت حذف شد');
    }

    public function status(Category $category)
    {
        $this->categoryService->status($category);
        return to_route('admin.category.index')->with('alert-success', 'وضعیت دسته بندی شما با موفقیت تغییر کرد !');
    }
}
