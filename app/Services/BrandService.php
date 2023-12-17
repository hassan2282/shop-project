<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\Brand\BrandRepositoryInterface;

class BrandService
{
//    protected BrandRepositoryInterface $brandRepository;
    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = app()->make(BrandRepositoryInterface::class);
    }


    public function store($request)
    {
        $brand =  $this->brandRepository->store($request);
        if ($brand) {
            return to_route('admin.brand.index')->with('alert-success', 'برند شما با موفقیت اضافه شد!');
        } else {
            return back()->withInput();
        }
    }
}
