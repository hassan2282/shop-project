<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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

    public function store($request): Model
    {
        $brand =  $this->brandRepository->create(attributes: $request->toArray());
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . trim($file->getClientOriginalName());
            $file->storeAs('public/', $name);

            $media = [
                'name' => $name,
                'mimetype' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mediable_type' => Brand::class,
                'mediable_id' => $brand->id,
            ];

            $this->mediaRepository->create(attributes: $media);
            return $brand;
        }
    }
}
