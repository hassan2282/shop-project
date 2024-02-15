<?php

namespace App\Repositories\Permission;

use App\Models\Admin\Permission;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
public function __construct(Permission $permission)
{
    parent::__construct($permission);
}
}
