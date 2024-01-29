<?php

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepositoryInterface;

interface ProductRepositoryInterface extends EloquentRepositoryInterface
{
    public function getDataForIndexProduct();

    public function getCategories();

    public function getBrands();

}
