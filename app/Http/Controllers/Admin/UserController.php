<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserWithRolesResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = UserWithRolesResource::collection(User::all())->resolve();
        $roles = RoleResource::collection(Role::all())->resolve();
        return inertia('Admin/User/Index', compact('users', 'roles'));
    }
}
