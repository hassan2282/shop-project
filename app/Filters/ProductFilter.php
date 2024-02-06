<?php

namespace App\Filters;

use App\Models\Admin\Product;

class ProductFilter
{
    private $queryParams;
    private $per_page;
    private $searchKey;
    private $status;
    private $result;
    private $query;
    private $brand;
    private $category;
    private $smaller;
    private $bigger;
    public function __construct($queryParams, $per_page)
    {
        $this->queryParams = $queryParams;
        $this->per_page = $per_page;
        $this->query = Product::query();
        $this->filter();
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

    private function createQuery()
    {
        if ($this->searchKey) {
            $this->query->where(function ($query){
                $query->where('name','like','%'. $this->searchKey .'%')
                    ->orWhere('id',$this->searchKey);
            });

        }
        if ($this->status) {
            if ($this->status === 'active') {
                $this->query->where('status', 1);
            } else {
                $this->query->where('status', 0);
            }
        }
        if ($this->brand) {
            $this->query->whereHas('brand', function ($q){
               $q->where('original_name','like','%'. $this->brand .'%');
            });
        }
        if ($this->category) {
            $this->query->whereHas('category', function($q){
               $q->where('name','like','%'. $this->category .'%');
            });
        }
        if ($this->smaller) {
            $this->query->where('price','<', $this->smaller);
        }
        if ($this->bigger) {
            $this->query->where('price', '>', $this->bigger);
        }
    }

    private function fetchData()
    {
        $this->result = $this->query->orderBy('id','desc')->paginate($this->per_page);
    }

    private function filter()
    {
        $this->extractSearchKey();
        $this->extractStatus();
        $this->extractBrand();
        $this->extractCategory();
        $this->extractSmaller();
        $this->extractBigger();
        $this->createQuery();
        $this->fetchData();
    }

    public function getResult()
    {
        return $this->result;
    }
}
