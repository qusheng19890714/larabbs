<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;

class CategoriesController extends Controller
{

    /**
     * 分类下的帖子列表页
     * @param Category $category
     */
    public function show(Request $request, Category $category, Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);

        // 活跃用户列表
        $active_users = $user->getActiveUsers();

        return view('topics.index', compact('topics', 'category', 'active_users'));
    }
}
