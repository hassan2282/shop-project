<?php

namespace App\Repositories\Brand;

use App\Repositories\EloquentRepositoryInterface;

interface BrandRepositoryInterface extends EloquentRepositoryInterface
{
    public function getBrandsWithFilters();
}
