<?php

namespace App\Repositories\AttributeValue;

use App\Models\Admin\AttributeValue;
use App\Repositories\BaseRepository;

class AttributeValueRepository extends BaseRepository implements AttributeValueRepositoryInterface
{
    public function __construct(AttributeValue $attributeValue)
    {
        parent::__construct($attributeValue);
    }
}
