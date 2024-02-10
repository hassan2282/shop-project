<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerStoreRequest;
use App\Http\Requests\Admin\Banner\BannerUpdateRequest;
use App\Models\Admin\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Services\BannerService;
use App\Services\SaveImage;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function __construct(readonly protected BannerRepositoryInterface $bannerRepository,
                                readonly protected BannerService             $bannerService)
    {
    }

    public function index()
    {
        $banners = $this->bannerRepository->allWithPaginate();
        return view('admin.banner.index', compact('banners'));
    }


    public function create()
    {
        return view('admin.banner.create');
    }


    public function store(BannerStoreRequest $bannerRequest)
    {
        $this->bannerService->create($bannerRequest);
        return redirect()->route('admin.banner.index')->with('alert-success', 'بنر شما با موفقیت اضافه شد!');
    }


    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }


    public function update(Banner $banner, BannerUpdateRequest $bannerRequest)
    {
        $this->bannerService->update($banner, $bannerRequest);
        return redirect()->route('admin.banner.index')->with('alert-success', 'بنر شما با موفقیت ویرایش شد!');
    }


    public function delete(Banner $banner)
    {
        $this->bannerService->delete($banner);
        return to_route('admin.banner.index')->with('alert-success', 'بنر شما با موفقیت حذف شد!');
    }


    public function status(Banner $banner)
    {
        $this->bannerService->status($banner);
        return to_route('admin.banner.index')->with('alert-success', 'وضعیت بنر شما با موفقیت تغییر کرد !');
    }


}
