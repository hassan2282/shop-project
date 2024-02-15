<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleStoreRequest;
use App\Http\Requests\Admin\Role\RoleUpdateRequest;
use App\Models\Admin\Role;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class RoleController extends Controller
{

    public function __construct(readonly protected RoleRepositoryInterface       $roleRepository,
                                readonly protected PermissionRepositoryInterface $permissionRepository)
    {
    }

    public function index(): View
    {
        $roles = $this->roleRepository->allWithPaginate();
        return view('admin.role.index', compact('roles'));
    }


    public function create(): View
    {
        $permissions = $this->permissionRepository->all();
        return view('admin.role.create', compact('permissions'));
    }


    public function store(RoleStoreRequest $request)
    {
        $role = $this->roleRepository->create($request->toArray());
        $role->permissions()->sync($request['permissions']);
        return to_route('admin.role.index')->with('alert-success', 'نقش جدید با موفقیت ایجاد شد!');
    }


    public function edit(Role $role): View
    {
        $permissions = $this->permissionRepository->all();
        return view('admin.role.edit', compact('role', 'permissions'));
    }


    public function update(Role $role, RoleUpdateRequest $request): RedirectResponse
    {
        $result = $this->roleRepository->update($request->toArray(),$role->id);
        $role->permissions()->sync($request['permissions']);
        return to_route('admin.role.index')->with('alert-success', 'نقش شما با موفقیت ویرایش شد!');
    }


    public function delete(Role $role)
    {
        $this->roleRepository->delete($role->id);
        return back();
    }


}

