<?php

namespace App\Services;

use App\Models\Admin\Attribute;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    public function __construct(readonly protected ProductRepositoryInterface $productRepository)
    {
    }

    public function create($productRequest, $saveImage)
    {
        $inputs = $productRequest->all();
        $image = $productRequest->file('image');
        $saveImage->save($image, 'Products');
        $inputs['image'] = $saveImage->saveImageDb();

        $this->productRepository->create($inputs, $image);

        if (isset($inputs['attributes'])) {
            $attributes = collect($inputs['attributes']);
            $attributes->each(function ($item) use ($product) {
                if (is_null($item['name']) || is_null($item['value'])) return;

                $attr = Attribute::create([
                    'name' => $item['name']
                ]);


                $attr_value = $attr->values()->create([
                    'value' => $item['value']
                ]);

                $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);


            });
        }


        return to_route('admin.product.index')->with('محصول جدید شما با موفقیت اضافه شد!');
    }

    public function getCategories()
    {
        $categories = $this->productRepository->getCategories();
        if ($categories) {
            return $categories;
        } else {
            return 'we have no category in database!';
        }
    }

    public function getBrands()
    {
        $brands = $this->productRepository->getBrands();
        if ($brands) {
            return $brands;
        } else {
            return 'we have no category in database!';
        }
    }
}
