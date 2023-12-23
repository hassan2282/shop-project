<?php

namespace App\Services;

use App\Models\Admin\Category;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use function Sodium\add;

class CategoryService extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function all(): Collection
    {
        return $this->model->all('id', 'name');
    }

    public function status($category)
    {
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
    }

    public function create(array $attributes): Category
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, $id): bool
    {
        return $this->model->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
