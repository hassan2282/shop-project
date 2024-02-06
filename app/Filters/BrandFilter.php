<?php

namespace App\Filters;

use App\Models\Admin\Brand;
use Illuminate\Database\Eloquent\Model;

class BrandFilter extends BaseFilter
{
    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
    }

}
