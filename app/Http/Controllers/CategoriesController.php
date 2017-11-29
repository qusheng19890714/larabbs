<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{

    /**
     * 分类下的帖子列表页
     * @param Category $category
     */
    public function show(Category $category)
    {
        $topics = Topic::with('category', 'user')->where('category_id', $category->id)->paginate(20);

        return view('topics.index', compact('topics', 'category'));
    }
}
