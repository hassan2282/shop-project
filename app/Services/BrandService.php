<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\Brand\BrandRepositoryInterface;

class BrandService
{
    protected BrandRepositoryInterface $brandRepository;
    public function __construct()
    {
        $this->brandRepository = app()->make(BrandRepositoryInterface::class);
    }


    public function store($request)
    {
        return $this->brandRepository->store($request);
    }
}
