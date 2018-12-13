<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except'=>['show']]);
    }
    //个人展示页面get
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    //编辑个人中心get
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    //保存编辑数据put
    public function update(UserRequest $request,User $user,ImageUploadHandler $uploader)
    {
        $this->authorize('update', $user);
        $data = $request->all();
        if ($request->avatar) {
//            $result = $uploader->save($request->file('avatar'), 'avatars', $user->id);
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return  redirect()->route('users.show',$user->id)->with('success','个人信息更新成功');
    }
}
