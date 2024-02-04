<?php

namespace App\Filters;

use App\Models\Admin\Brand;

class BrandFilter
{
    private $per_page;
    private $queryParam;
    private $searchKey;
    private $result;
    private $sort;
    private $query;
    private $status;


    public function __construct($queryParams, $per_page)
    {
        $this->queryParam = $queryParams;
        $this->per_page = $per_page;
        $this->query = Brand::query();
        $this->filter();
    }

    private function extractKeyByKeyName($key)
    {
        if (isset($this->queryParam[$key])) {
            $q = $this->queryParam[$key];
            unset($this->queryParam[$key]);
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

    private function createQuery()
    {
        if ($this->searchKey) {
            $this->query->where('original_name', 'like', '%' . $this->searchKey . '%')
                ->orwhere('description', 'like', '%' . $this->searchKey . '%');
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
        $this->result =  $this->query->orderBy('id','desc')->paginate($this->per_page);
    }

    private function filter()
    {
        $this->extractSearchKey();
        $this->extractStatus();
        $this->createQuery();
        $this->fetchData();
    }

    public function getResult()
    {
        return $this->result;
    }
}
