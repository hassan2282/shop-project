<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\PermissionStoreRequest;
use App\Http\Requests\Admin\Permission\PermissionUpdateRequest;
use App\Models\Admin\Permission;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct(readonly protected PermissionRepositoryInterface $permissionRepository,
                                readonly protected PermissionService             $permissionService)
    {
    }


    public function index(): View
    {
        $permissions = $this->permissionRepository->allWithPaginate();
        return view('admin.permission.index', compact('permissions'));
    }


    public function create(): View
    {
        return view('admin.permission.create');
    }


    public function store(PermissionStoreRequest $request): RedirectResponse
    {
        $this->permissionRepository->create($request->toArray());
        return to_route('admin.permission.index')->with('alert-success', 'دسترسی جدید با موفقیت ایجاد شد!');
    }


    public function edit(Permission $permission): View
    {
        return view('admin.permission.edit', compact('permission'));
    }


    public function update(Permission $permission, PermissionUpdateRequest $request): RedirectResponse
    {
        $this->permissionRepository->update($request->toArray(), $permission->id);
        return to_route('admin.permission.index')->with('alert-success', 'دسترسی شما با موفقیت ویرایش شد!');
    }


    public function delete(Permission $permission): RedirectResponse
    {
        $this->permissionRepository->delete($permission->id);
        return back();
    }


}
