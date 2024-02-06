<?php

namespace App\Filters;

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

    protected function createQuery()
    {
        if ($this->searchKey) {
            $this->query->where (function ($query) {
                foreach ($this->columns as $column) {
                    $query->where ($column, 'LIKE', '%'. $this->searchKey. '%')
                        ->orWhere ($column, 'LIKE', '%'. $this->searchKey. '%') ;
                }
            }) ;
        }

        if ($this->status) {
            if ($this->status === 'active') {
                $this->query->where('status', 1);
            } else {
                $this->query->where('status', 0);
            }
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
        $this->createQuery();
        $this->fetchData();
    }
}
