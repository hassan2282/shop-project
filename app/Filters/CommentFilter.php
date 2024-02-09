<?php

namespace App\Filters;

use App\Models\Admin\Comment;

class CommentFilter extends BaseFilter
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }
}
