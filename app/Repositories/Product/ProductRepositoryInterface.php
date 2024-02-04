<?php

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepositoryInterface;

interface ProductRepositoryInterface extends EloquentRepositoryInterface
{
    public function getProductsByFilters();

    public function getCategories();

    public function getBrands();

}
