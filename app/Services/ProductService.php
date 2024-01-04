<?php

namespace App\Services;

use App\Models\Admin\Attribute;
use App\Models\Admin\Product;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(readonly protected ProductRepositoryInterface $productRepository,
                                readonly protected MediaRepositoryInterface   $mediaRepository)
    {
    }

    public function create($request)
    {
        dd($request);
        $product = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
            'sold_number' => $request->sold_number,
            'frozen_number' => $request->frozen_number,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ];
        $productCreate = $this->productRepository->create($product);

        if ($request->file('media')) {
            $image = $request->file('media');
            $image_name = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products/', $image_name);

            $media = [
                'name' => $image_name,
                'size' => $image->getSize(),
                'mimetype' => $image->getMimeType(),
                'mediable_id' => $productCreate->id,
                'mediable_type' => Product::class,
            ];

            $mediaStore = $this->mediaRepository->create($media);
        }
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
