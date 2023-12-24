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
        File::delete('/adm/products/images/' . $brand->logo);
        $brand->delete();
    }

    public function status($brand)
    {
        $brand->status = $brand->status == 1 ? 0 : 1;
        $brand->save();
    }
}
