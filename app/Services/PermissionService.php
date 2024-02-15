<?php

namespace App\Services;

use App\Repositories\Permission\PermissionRepositoryInterface;

class PermissionService
{
    public function __construct(readonly protected PermissionRepositoryInterface $permissionRepository)
    {
    }
}
