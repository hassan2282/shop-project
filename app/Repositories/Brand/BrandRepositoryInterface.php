<?php

namespace App\Repositories\Brand;

interface BrandRepositoryInterface
{
    public function index();

    public function create($request);

    public function update($brand, $request);

    public function delete($brand);

    public function status($brand);
}
