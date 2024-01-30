<?php

namespace App\Repositories\Attribute;

use App\Models\Admin\Attribute;
use App\Repositories\BaseRepository;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute);
    }
}
