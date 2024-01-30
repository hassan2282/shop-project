<?php

namespace App\Services;

use App\Models\Admin\Attribute;
use App\Models\Admin\Product;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ProductService
{
    public function __construct(readonly protected ProductRepositoryInterface $productRepository,
                                readonly protected MediaRepositoryInterface   $mediaRepository)
    {
    }

    public function create(Request $request): RedirectResponse
    {
        $product = $request->except('media','attributes');
        $productCreate = $this->productRepository->create($product);
            if ($request->file('media')) {
                $image = $request->file('media');
                $image_name = Str::random(20) . '.' . $image->getClientOriginalExtension();


                if (getimagesize($image)[0] > 800) {
                    FFMpeg::open($image)
                        ->addFilter(['-s', '800x600'])
                        ->export()
                        ->toDisk('public')
                        ->save('products/' . $image_name);
                } else {
                    $image->storeAs('public/products/', $image_name);
                }

                FFMpeg::fromDisk('public')
                    ->open('products/' . $image_name)
                    ->addFilter(['-s', '320x240'])
                    ->export()
                    ->toDisk('public')
                    ->save('thumbnails/' . $image_name);

                $media = [
                    'name' => $image_name,
                    'size' => $image->getSize(),
                    'mimetype' => $image->getMimeType(),
                    'mediable_id' => $productCreate->id,
                    'mediable_type' => Product::class,
                ];

                $mediaStore = $this->mediaRepository->create($media);
            }
        if ($request['attributes']) {
            $attributes = collect($request['attributes']);
            $attributes->each(function ($item) use ($product) {
                if (is_null($item['name']) || is_null($item['value'])) return;

                $attr = Attribute::create([
                    'name' => $item['name'],
                    'product_id' => $productCreate->id,
                ]);


                $attr_value = $attr->values()->create([
                    'value' => $item['value']
                ]);

//                $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);

            });
        }


        return to_route('admin.product.index')->with('محصول جدید شما با موفقیت اضافه شد!');
    }

    public function getCategories(): mixed
    {
        $categories = $this->productRepository->getCategories();
        if ($categories) {
            return $categories;
        } else {
            return 'we have no category in database!';
        }
    }

    public function getBrands(): mixed
    {
        $brands = $this->productRepository->getBrands();
        if ($brands) {
            return $brands;
        } else {
            return 'we have no category in database!';
        }
    }

    public function delete(Product $product)
    {

    }
}
