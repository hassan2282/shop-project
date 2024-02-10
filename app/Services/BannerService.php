<?php

namespace App\Services;

use App\Http\Requests\Admin\Banner\BannerStoreRequest;
use App\Http\Requests\Admin\Banner\BannerUpdateRequest;
use App\Models\Admin\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $image_name = time() . Str::random(20) .'.'. $file->getClientOriginalExtension();

            if (getimagesize($file)[1] > 800) {
                \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($file)
                    ->addFilter(['-s', '800x600'])
                    ->export()
                    ->toDisk('public')
                    ->save('banners/'. $image_name);
            } else {
                $file->storeAs('public/banners/', $image_name);
            }

            \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($file)
                ->addFilter(['-s', '300x240'])
                ->export()
                ->toDisk('public')
                ->save('thumbnails/' . $image_name) ;


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

    public function update(Banner $banner, BannerUpdateRequest $request)
    {
        $this->bannerRepository->update($request->except('image'), $banner->id);
        if ($request->hasFile('image')) {
            if ($banner->media) {
                $this->mediaRepository->delete($banner->media->id);
                Storage::disk('public')->delete('banners/'. $banner->media->name);
                Storage::disk('public')->delete('thumbnails/'. $banner->media->name);
            }
            $image = $request->file('image');
            $image_name = Str::random(20) . '.' . $image->getClientOriginalExtension();

            if (getimagesize($image)[1] > 800) {
                \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($image)
                    ->addFilter(['-s', '800x600'])
                    ->export()
                    ->toDisk('public')
                    ->save('banners/'. $image_name);
            } else {
                $image->storeAs('public/banners/', $image_name);
            }
            \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::open($image)
                ->addFilter(['-s', '300x200'])
                ->export()
                ->toDisk('public')
                ->save('thumbnails/'. $image_name);

            $media = [
                'name' => $image_name,
                'mimetype' => $image->getMimeType(),
                'size' => $image->getSize(),
                'mediable_type' => Banner::class,
                'mediable_id' => $banner->id,
            ];
            $this->mediaRepository->create(attributes: $media);

        }

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
