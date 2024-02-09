<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BaseFilter
{
    protected $model;
    private $queryParams;
    private $per_page;
    private $searchKey;
    private $query;
    private $status;
    private $result;
    private $columns;
    private $author;
    private $parent;
    private $brand;
    private $category;
    private $smaller;
    private $bigger;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getByFilter(array $queryParams, int $per_page, array $columns)
    {
        $this->query = $this->model->query();
        $this->queryParams = $queryParams;
        $this->per_page = $per_page;
        $this->columns = $columns;
        $this->filter();
        return $this->result;
    }

    private function extractKeyByKeyName($key)
    {
        if (isset($this->queryParams[$key])) {
            $q = $this->queryParams[$key];
            unset($this->queryParams[$key]);
            return $q;
        }
    }

    private function extractSearchKey()
    {
        $this->searchKey = $this->extractKeyByKeyName('q');
    }

    private function extractStatus()
    {
        $this->status = $this->extractKeyByKeyName('status');
    }
    private function extractAuthor()
    {
        $this->author = $this->extractKeyByKeyName('author');
    }
    private function extractParent()
    {
        $this->parent = $this->extractKeyByKeyName('parent');
    }
    private function extractBrand()
    {
        $this->brand = $this->extractKeyByKeyName('brand');
    }
    private function extractCategory()
    {
        $this->category = $this->extractKeyByKeyName('category');
    }
    private function extractSmaller()
    {
        $this->smaller = $this->extractKeyByKeyName('smaller');
    }
    private function extractBigger()
    {
        $this->bigger = $this->extractKeyByKeyName('bigger');
    }

    protected function createQuery()
    {
        if ($this->searchKey) {
            $this->query->where(function ($query) {
                foreach ($this->columns as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $this->searchKey . '%');
                }
            });
        }

        //use for all filter
        if ($this->status) {
            if ($this->status === 'active') {
                $this->query->where('status', 1);
            } else {
                $this->query->where('status', 0);
            }
        }

        //use for Comment filter
        if ($this->author) {
            $this->query->whereHas('user', function ($query){
                $query->where('first_name','like','%'. $this->author .'%');
            });
        }

        //use for Comment filter
        if ($this->parent) {
            $this->query->where(function ($query){
            $query->where('parent_id', $this->parent)
                ->orWhere(function ($q){
                    $q->WhereHas('parent', function ($query) {
                        $query->where('body','like','%'.$this->parent.'%');
                    });
                });
            });
        }

        //use for Product Filter
        if ($this->brand) {
            $this->query->whereHas('brand', function ($q){
               $q->where('original_name','like','%'. $this->brand .'%');
            });
        }
        //use for Product Filter
        if ($this->category) {
            $this->query->whereHas('category', function($q){
               $q->where('name','like','%'. $this->category .'%');
            });
        }
        //use for Product Filter
        if ($this->smaller) {
            $this->query->where('price','<', $this->smaller);
        }
        //use for Product Filter
        if ($this->bigger) {
            $this->query->where('price', '>', $this->bigger);
        }

    }

    private function fetchData()
    {
        $this->result = $this->query->orderBy('id', 'desc')->paginate($this->per_page);
    }

    private function filter()
    {
        $this->extractSearchKey();
        $this->extractStatus();
        $this->extractAuthor();
        $this->extractParent();
        $this->extractBrand();
        $this->extractCategory();
        $this->extractSmaller();
        $this->extractBigger();
        $this->createQuery();
        $this->fetchData();
    }
}
