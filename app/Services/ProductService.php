<?php

namespace App\Services;

use App\Models\Admin\Attribute;
use App\Models\Admin\Product;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\AttributeValue\AttributeValueRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $product = $request->except('media', 'attributes');
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
            $attributes->each(function ($item) use ($productCreate) {
                if (is_null($item['name']) || is_null($item['value'])) return;
                $attr = Attribute::create([
                    'name' => $item['name'],
                    'product_id' => $productCreate->id,
                ]);


                $attr_value = $attr->value()->create([
                    'value' => $item['value']
                ]);

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

    public function update($request): RedirectResponse
    {
        // Find the product to update
        $product = Product::findOrFail($id);

        // Update Product data
        $inputs = $productRequest->all();

        // Check if a new image is uploaded
        $newImage = $productRequest->file('image');
        if (!is_null($newImage)) {
            // Save the new image
            $saveImage->save($newImage, 'Products');
            $inputs['image'] = $saveImage->saveImageDb();

            // Delete the old image if necessary
            if (!is_null($product->image)) {
                File::delete(public_path($product->image));
            }
        }

        $product->update($inputs);

        // Update attributes
        if (!is_null($inputs['attributes'])) {
            $attributes = collect($inputs['attributes']);
            $attributes->each(function ($item) use ($product) {
                if (is_null($item['name']) || is_null($item['value'])) return;

                $attr = Attribute::firstOrCreate([
                    'name' => $item['name']
                ]);

                $attrValue = $attr->values()->firstOrCreate([
                    'value' => $item['value']
                ]);

                // Sync or attach the attribute and value to the product
                $product->attributes()->syncWithoutDetaching([
                    $attr->id => ['value_id' => $attrValue->id]
                ]);
            });
        }

        return redirect()->route('admin.product.index')->with('success', 'محصول با موفقیت به‌روزرسانی شد!');
    }

    public function delete(Product $product): void
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
