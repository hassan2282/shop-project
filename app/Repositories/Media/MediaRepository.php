<?php

namespace App\Repositories\Media;

use App\Models\Media;
use App\Repositories\BaseRepository;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function __construct(Media $media)
    {
        parent::__construct($media);
    }
}
