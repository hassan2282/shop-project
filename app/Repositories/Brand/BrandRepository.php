<?php

namespace App\Repositories\Brand;

use App\Models\Admin\Brand;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
    }

    public function store($request, $image_name)
    {
        return Brand::create([
            'persian_name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'logo' => $image_name,
        ]);

    }

//    public function update($brand, $request)
//    {
//        $inputs = $request->all();
//        if ($request->hasFile('logo')) {
//            File::delete(public_path($brand->logo));
//            $image = $request->file('logo');
////            $saveImage->save($image, 'Brands');
////            $inputs['logo'] = $saveImage->saveImageDb();
//        }
//        $brand->update($inputs);
//    }

    public function delete($brand)
    {
        File::delete(public_path($brand->logo));
        $brand->delete();
    }

    public function status($brand)
    {
        $brand->status = $brand->status == 1 ? 0 : 1;
        $brand->save();
    }
}
