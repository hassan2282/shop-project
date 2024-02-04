<?php

namespace App\Repositories\Brand;

use App\Filters\BrandFilter;
use App\Models\Admin\Brand;
use App\Repositories\BaseRepository;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
    }

    public function getBrandsWithFilters()
    {
        $queryParams = [
            'q' => request()->q,
            'status' => request()->status,
        ];
        return (new BrandFilter($queryParams, 15))->getResult();
    }
    public function status($brand)
    {
        $brand->status = $brand->status == 1 ? 0 : 1;
        $brand->save();
    }
}
