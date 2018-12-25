<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    //获取用户登陆的权限
    public function index()
    {
        $permissions = $this->user()->getAllpermissions();
        return PermissionResource::collection($permissions);
    }
}
