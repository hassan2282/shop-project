<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BrandService extends BaseRepository
{
    protected BrandRepositoryInterface $brandRepository;
    protected MediaRepositoryInterface $mediaRepository;

    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
        $this->brandRepository = app()->make(BrandRepositoryInterface::class);
        $this->mediaRepository = app()->make(MediaRepositoryInterface::class);
    }

    public function store($request)
    {
        $brand = $this->brandRepository->create(attributes: $request->toArray());
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . Str::random(20) .'.'. $file->getClientOriginalExtension();
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
}
