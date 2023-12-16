<?php

namespace App\Repositories\Brand;

use App\Models\Admin\Brand;
use Illuminate\Support\Facades\File;

class BrandRepository
{
    public function index()
    {
        return Brand::Paginate(15);
    }

    public function create($request)
    {
        $brand = Brand::create([
            'persian_name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'logo' => '',
        ]);
    }

    public function update($brand, $request)
    {
        $inputs = $request->all();
        if ($request->hasFile('logo')) {
            File::delete(public_path($brand->logo));
            $image = $request->file('logo');
//            $saveImage->save($image, 'Brands');
//            $inputs['logo'] = $saveImage->saveImageDb();
        }
        $brand->update($inputs);
    }

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
