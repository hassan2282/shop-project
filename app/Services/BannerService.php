<?php

namespace App\Services;

use App\Http\Requests\Admin\Banner\BannerStoreRequest;
use App\Http\Requests\Admin\Banner\BannerUpdateRequest;
use App\Models\Admin\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class BannerService
{
    public function __construct(readonly protected BannerRepositoryInterface $bannerRepository,
                                readonly protected MediaRepositoryInterface  $mediaRepository)
    {
    }

    public function create(BannerStoreRequest $bannerRequest)
    {
        $banner = $this->bannerRepository->create($bannerRequest->except('image'));
        if ($bannerRequest->hasFile('image')) {
            $file = $bannerRequest->file('image');
            $image_name = time() . Str::random(20) . $file->getClientOriginalExtension();

            if (getimagesize($file)[1] > 800) {
                FFMpeg::open($file)
                    ->addFilter(['-s', '800x600'])
                    ->export()
                    ->toDisk('public')
                    ->save('banners/' . $image_name);
            } else {
                $file->storeAs('public/banners/', $image_name);
            }
            FFMpeg::open($file)
                ->addFilter(['-s', '300x240'])
                ->export()
                ->toDisk('public')
                ->save('banners/thumbnail/' . $image_name);


            $media = [
                'name' => $image_name,
                'size' => $file->getSize(),
                'mimetype' => $file->getMimeType(),
                'mediable_id' => $banner->id,
                'mediable_type' => Banner::class,
            ];
            $this->mediaRepository->create($media);
        }
    }

    public function update(Banner $banner, BannerUpdateRequest $bannerRequest)
    {
        if ($bannerRequest->hasFile('image')) {
            // delete file
            File::delete(public_path($banner->image));

            // update file
            $file = $bannerRequest->file('image');
            $saveImage->save($file, '/banners/');
            $inputs['image'] = $saveImage->saveImageDb();
        }

        $banner->update($inputs);
    }

    public function status(Banner $banner)
    {
        $banner->status = $banner->status == 1 ? 0 : 1;
        $banner->save();
    }

    public function delete(Banner $banner)
    {
        $this->bannerRepository->delete($banner->id);
        File::delete(public_path($banner->image));
    }
}
