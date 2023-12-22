<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use Illuminate\Support\Collection;

class BrandService extends BaseRepository
{
    protected BrandRepositoryInterface $brandRepository;
    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
        $this->brandRepository = app()->make(BrandRepositoryInterface::class);
    }

    public function store($request)
    {
        $logo = $request->file('logo');
        $image_name = time() . '-' . trim($logo->getClientOriginalName());
        $logo->move('adm/products/images', $image_name);
        return $this->brandRepository->store($request, $image_name);
    }
}
