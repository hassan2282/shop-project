<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    public function __construct(readonly protected BrandRepositoryInterface $brandRepository,
                                readonly protected MediaRepositoryInterface $mediaRepository)
    {
    }

    public function store($request)
    {
        $brand = $this->brandRepository->create(attributes: $request->toArray());
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/brands/', $name);

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
            $image = $request->file('logo');
            $image_name = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/brands/', $image_name);

            $media = [
                'name' => $image_name,
                'mimetype' => $image->getMimeType(),
                'size' => $image->getSize(),
                'mediable_type' => Brand::class,
                'mediable_id' => $targetBrand->id,
            ];
            $deletedMedia = Storage::disk('public')->delete('brands/' . $targetBrand->media->name);
            $targetMedia = $this->mediaRepository->where('mediable_id', $id);
            $updatedMedia = $this->mediaRepository->update($media, $targetMedia[0]['id']);
        }
        if (isset($brand) && isset($deletedMedia) && isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد و تصویر جدید با موفقیت جایگزین تصویر قبل شد همراه با حذف تصویر قبل از دیتابیس!');
        } elseif (isset($brand) && isset($deletedMedia) && !isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد و تصویر قبلی از دیتابیس پاک شد ولی تصویر جدیدی جایگزین نشد!');
        } elseif (isset($brand) && !isset($deletedMedia) && isset($updatedMedia)) {
            return redirect(route('admin.brand.index'))->with('alert-success', 'برند شما با موفقیت ویرایش شد در فراید حذف تصویر قبلی از دیتابیس مشکلی بوجود آمده است!');
        } else {
            return redirect(route('admin.brand.index'))->with('alert-error','متاسفانه خطایی بوجود آمده است');
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
