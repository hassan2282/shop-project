<?php

namespace App\Repositories\Product;

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

    public function getDataForIndexProduct()
    {
        return Product::with(['brand:id,name', 'category:id,name'])
            ->select(['id', 'name', 'slug', 'price', 'status'])
            ->paginate();
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
