<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['show']]);
    }


    /**
     * 个人中心
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 修改个人资料
     */

    public function edit(User $user)
    {
        //策略验证
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     *个人资料修改
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        //策略验证
        $this->authorize('update', $user);

        $data = $request->all();

        //头像
        if ($request->avatar) {

            $result = $uploader->save($request->avatar, 'avatars', $user->id, '361');

            if ($request) {

                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
