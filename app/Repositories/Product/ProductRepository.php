<?php

namespace App\Repositories\Product;

use App\Filters\ProductFilter;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function getProductsByFilters()
    {
        $queryParams = [
            'q' => request()->q,
            'status' => request()->status,
            'brand' => request()->brand,
            'category' => request()->category,
            'smaller' => request()->smaller,
            'bigger' => request()->bigger
        ];
        return (new ProductFilter($queryParams, 15))->getResult();
    }

    public function getCategories()
    {
        return Category::get(['id','name']);
    }

    public function getBrands()
    {
        return Brand::get(['id','original_name']);
    }
}
