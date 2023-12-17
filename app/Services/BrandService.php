<?php

namespace App\Services;

use App\Repositories\Brand\BrandRepositoryInterface;

class BrandService
{
    protected BrandRepositoryInterface $brandRepository;
    public function __construct()
    {
        $this->brandRepository = app()->make(BrandRepositoryInterface::class);
    }

    public function store($brand, $request)
    {
        if ($brand) {
            return to_route('admin.brand.index')->with('alert-success', 'برند شما با موفقیت اضافه شد!');
        } else {
            return back()->withInput();
        }
        return $this->brandRepository->store($request);
    }
}
