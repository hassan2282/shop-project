<?php

namespace App\Filters;

use App\Models\Admin\Comment;

class CommentFilter extends BaseFilter
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }
//    private $queryParams;
//    private $searchKey;
//    private $per_page;
//    private $query;
//    private $result;
//    private $status;
//    private $author;
//    private $parent;
//    public function __construct($queryParams, $per_page)
//    {
//        $this->queryParams = $queryParams;
//        $this->query = Comment::query();
//        $this->per_page = $per_page;
//        $this->filter();
//    }
//
//    private function extractKeyByKeyName($key)
//    {
//        if (isset($this->queryParams[$key])) {
//            $q = $this->queryParams[$key];
//            unset($this->queryParams[$key]);
//            return $q;
//        }
//    }
//
//    private function extractSearchKey()
//    {
//        $this->searchKey = $this->extractKeyByKeyName('q');
//    }
//
//    private function extractStatus()
//    {
//        $this->status = $this->extractKeyByKeyName('status');
//    }
//    private function extractAuthor()
//    {
//        $this->author = $this->extractKeyByKeyName('author');
//    }
//    private function extractParent()
//    {
//        $this->parent = $this->extractKeyByKeyName('parent');
//    }
//
//    private function createQuery()
//    {
//        if ($this->searchKey) {
//            $this->query->where(function ($query){
//                $this->query->where('id', $this->searchKey)
//                    ->orWhere('body','like','%'.$this->searchKey.'%');
//            });
//
//        }
//        if ($this->status) {
//            if ($this->status === 'active') {
//                $this->query->where('status', 1);
//            } else {
//                $this->query->where('status', 0);
//            }
//        }
//        if ($this->author) {
//            $this->query->whereHas('user', function ($q) {
//                $q->where('first_name','like','%'.$this->author.'%');
//            });
//        }
//        if ($this->parent) {
//            $this->query->where('parent_id', $this->parent);
//        }
//    }
//
//    private function fetchData()
//    {
//        $this->result = $this->query->orderBy('id','desc')->paginate($this->per_page);
//    }
//
//    private function filter()
//    {
//        $this->extractSearchKey();
//        $this->extractStatus();
//        $this->extractAuthor();
//        $this->extractParent();
//        $this->createQuery();
//        $this->fetchData();
//    }
//
//    public function getResult()
//    {
//        return $this->result;
//    }
}
