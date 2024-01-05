<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\brandStoreRequest;
use App\Http\Requests\Admin\Brand\brandUpdateRequest;
use App\Models\Admin\Brand;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(readonly protected BrandRepositoryInterface $brandRepository,
                                readonly protected BrandService             $brandService)
    {
    }

    public function index()
    {
        $brands = $this->brandRepository->allWithPaginate();
        return view('admin.brand.index', compact('brands'));
    }


    public function create()
    {
        return view('admin.brand.create');
    }


    public function store(brandStoreRequest $request)
    {
        return $this->brandService->store($request);
    }


    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }


    public function update(brandUpdateRequest $request, $id)
    {
        return $this->brandService->update($request, $id);
    }


    public function delete(int $id)
    {
        return $this->brandService->delete($id);
    }


    /* -- change status -- */
    public function status(Brand $brand)
    {
        $this->brandRepository->status($brand);
        return to_route('admin.brand.index')->with('alert-success', 'وضعیت برند شما با موفقیت تغییر کرد !');
    }


}
