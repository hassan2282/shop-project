<?php

namespace App\Services;

use App\Filters\BrandFilter;
use App\Models\Admin\Brand;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    public function __construct(readonly protected BrandRepositoryInterface $brandRepository,
                                readonly protected MediaRepositoryInterface $mediaRepository,
                                readonly protected BrandFilter              $brandFilter)
    {
    }

    public function getBrandsWithFilters()
    {
        $queryParams = [
            'q' => request()->q,
            'status' => request()->status,
        ];
        $columns = [
            'id',
            'original_name',
            'description',
        ];
        return $this->brandFilter->getByFilter($queryParams, 15, $columns);
    }

    public function store($request)
    {
        $brand = $this->brandRepository->create(attributes: $request->toArray());
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . Str::random(20) . '.' . $file->getClientOriginalExtension();
            if (getimagesize($file)[1] > 800) {
                \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($file)
                    ->addFilter(['-s', '800x600'])
                    ->export()
                    ->toDisk('public')
                    ->save('brands/'. $name);
            } else {
                $file->storeAs('public/brands/', $name);
            }
            \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($file)
                ->addFilter(['-s', '300x240'])
                ->export()
                ->toDisk('public')
                ->save('thumbnails/'. $name);

            $media = [
                'name' => $name,
                'mimetype' => $file->getMimeType(),
                'size' => $file->getSize(),
                'mediable_type' => Brand::class,
                'mediable_id' => $brand->id,
            ];

            $store_media = $this->mediaRepository->create(attributes: $media);
        }

        if ($brand && isset($store_media)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت اضافه شد [تصویر آپلود شد]!');
        } elseif ($brand && !isset($store_media)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت اضافه شد [تصویر آپلود نشد]!');
        } else {
            return back()->withInput();
        }
    }

    public function update($request, int $id)
    {
        $attributes = [
            'original_name' => $request->original_name,
            'description' => $request->description,
            'status' => $request->status
        ];
        $brand = $this->brandRepository->update($attributes, $id);
        $targetBrand = $this->brandRepository->find($id);
        if ($request->hasFile('logo')) {
            if ($targetBrand->media) {
                $this->mediaRepository->delete($targetBrand->media->id);
                Storage::disk('public')->delete('brands/'. $targetBrand->media->name);
                Storage::disk('public')->delete('thumbnails/'. $targetBrand->media->name);
            }

            $image = $request->file('logo');
            $image_name = Str::random(20) . '.' . $image->getClientOriginalExtension();

            if (getimagesize($image)[1] > 800) {
                \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($image)
                    ->addFilter(['-s', '800x600'])
                    ->export()
                    ->toDisk('public')
                    ->save('brands/'. $image_name);
            } else {
                $image->storeAs('public/brands/', $image_name);
            }
            \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($image)
                ->addFilter(['-s', '300x240'])
                ->export()
                ->toDisk('public')
                ->save('thumbnails/'. $image_name);

            $media = [
                'name' => $image_name,
                'mimetype' => $image->getMimeType(),
                'size' => $image->getSize(),
                'mediable_type' => Brand::class,
                'mediable_id' => $targetBrand->id,
            ];
            $this->mediaRepository->create(attributes: $media);
        }
        if (isset($brand) && isset($deletedMedia) && isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد و تصویر جدید با موفقیت جایگزین تصویر قبل شد همراه با حذف تصویر قبل از دیتابیس!');
        } elseif (isset($brand) && isset($deletedMedia) && !isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد و تصویر قبلی از دیتابیس پاک شد ولی تصویر جدیدی جایگزین نشد!');
        } elseif (isset($brand) && !isset($deletedMedia) && isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد در فراید حذف تصویر قبلی از دیتابیس مشکلی بوجود آمده است!');
        } else {
            return redirect(route('admin.brand.index'))->with('alert-error', 'متاسفانه خطایی بوجود آمده است');
        }
    }

    public function delete($id)
    {
        $brand = $this->brandRepository->find($id);
        $deletedBrand = $this->brandRepository->delete($id);
        if ($deletedBrand && $brand->media) {
            $media = $this->mediaRepository->where('mediable_id', $id);
            $this->mediaRepository->delete($media[0]['id']);
            Storage::disk('public')->delete('brands/' . $brand->media->name);
            return back()->with('success', 'برند و تصویر مربوطه حذف شدند');
        } elseif ($deletedBrand && !isset($brand->media)) {
            return back()->with('success', 'برند با موفقیت حذف شد -> تصویری یافت نشد!');
        } else {
            return back()->with('error', 'متاسفانه مشکلی بوجود آمده است');
        };
    }
}
