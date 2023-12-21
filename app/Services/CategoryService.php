<?php

namespace App\Services;

use App\Models\Admin\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CategoryService extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function all(): Collection
    {
        return $this->allWithPaginate();
    }
    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }

    public function store($request): Category
    {
        return $this->store($request);
    }

    public function update($attributes, $id): bool
    {
        return $this->update($attributes, $id);
    }
}
