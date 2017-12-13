<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //获取登录用户的所有通知
        $notifications = Auth::user()->notifications()->paginate(20);
        Auth::user()->markAsRead(); //将通知状态改为已读

        return view('notifications.index', compact('notifications'));
    }
}