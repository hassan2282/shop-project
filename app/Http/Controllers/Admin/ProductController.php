<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Models\Admin\Attribute;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\ProductService;
use App\Services\SaveImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(readonly protected ProductRepositoryInterface $productRepository,
                                readonly protected ProductService             $productService)
    {
    }

    public function index(): View
    {
        $products = $this->productRepository->getDataForIndexProduct();
        return view('admin.product.index', compact('products'));
    }


    public function create(): View
    {
        $categories = $this->productService->getCategories();
        $brands = $this->productService->getBrands();
        return view('admin.product.create', compact('categories', 'brands'));
    }


    public function store(ProductStoreRequest $request): RedirectResponse
    {
        return $this->productService->create($request);
    }


    public function edit(Product $product): View
    {
        $categories = $this->productRepository->getCategories();
        $brands = $this->productRepository->getBrands();
        return view('admin.product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        return $this->productService->update($request, $product);
    }


    public function delete(Product $product)
    {
        $this->productService->delete($product);
    }


    public function status(Product $product): RedirectResponse
    {
        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();
        return back()->with('وضعیت محصول شما با موفقیت تغییر کرد!');
    }


    public function attribute(Product $product): View
    {
        return view('admin.product.attribute', compact('product'));
    }

}
