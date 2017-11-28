<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
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
        return view('users.edit', compact('user'));
    }

    /**
     *个人资料修改
     */
    public function update(UserRequest $request, User $user)
    {

        $user->update($request->all());

        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
