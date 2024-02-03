<?php

namespace App\Services;

use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use App\Models\Admin\Product;
use App\Models\Media;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\AttributeValue\AttributeValueRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Disk;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ProductService
{
    public function __construct(readonly protected ProductRepositoryInterface        $productRepository,
                                readonly protected MediaRepositoryInterface          $mediaRepository,
                                readonly protected AttributeRepositoryInterface      $attributeRepository,
                                readonly protected AttributeValueRepositoryInterface $attributeValueRepository)
    {
    }

    public function create(Request $request): RedirectResponse
    {

        $productCreate = $this->productRepository->create($request->except('media', 'attributes'));

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
            $attr = [];
            $attr_value = [];
            $attributes->each(function ($item) use ($productCreate, &$attr, &$attr_value) {
                if (is_null($item['name']) || is_null($item['value'])) return;

                $attribute = $this->attributeRepository->create(['name' => $item['name']]);
                $attr[] = $attribute->id;

                $attributeValue = $this->attributeValueRepository->create(['value' => $item['value'] , 'product_id' => $productCreate->id ]);
                $attr_value[] = $attributeValue->id;
                $attribute->attribute_values()->sync($attr_value);

            });
            $productCreate->attributes()->sync($attr);
            $productCreate->attribute_values()->sync($attr_value);

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

    public function update(Request $request, Product $product): RedirectResponse
    {
        $productUpdate = $this->productRepository->update($request->except('media', 'attributes'), $product->id);

        if ($request->file('media')) {

            $this->mediaRepository->delete($product->media->id);
            Storage::disk('public')->delete('products/', $product->media->name);
            Storage::disk('public')->delete('thumbnails/', $product->media->name);

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
                'mediable_id' => $product->id,
                'mediable_type' => Product::class,
            ];

            $mediaStore = $this->mediaRepository->create($media);
        }

        if ($request['attributes']) {
            $product->attributes()->delete();
            $product->attribute_values()->delete();

            $attributes = collect($request['attributes']);
            $attr = [];
            $attr_value = [];

            $attributes->each(function ($item) use ($product ,&$attr, &$attr_value) {
                if (is_null($item['name']) || is_null($item['value'])) return;

                $attribute = $this->attributeRepository->create(['value' => $item['name']]);
                $attr[] = $attribute->id;

                $attributeValue = $this->attributeValueRepository->create(['value' => $item['value'], 'product_id' => $product->id ]);
                $attr_value[] = $attributeValue->id;
                $attribute->attribute_values()->sync($attr_value);
            });

            $product->attributes()->sync($attr);
            $product->attribute_values()->sync($attr_value);
        }

        return redirect()->route('admin.product.index')->with('success', 'محصول با موفقیت به‌روزرسانی شد!');
    }

    public
    function delete(Product $product): void
    {
        $this->mediaRepository->delete($product->media->id);
        Storage::disk('public')->delete('products/' . $product->media->name);
        Storage::disk('public')->delete('thumbnails/' . $product->media->name);
        foreach ($product->attributes as $attribute) {
            $this->attributeValueRepository->delete($attribute->value->id);
            $this->attributeRepository->delete($attribute->id);
        }
        $this->productRepository->delete($product->id);
    }
}
