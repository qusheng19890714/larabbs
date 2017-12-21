<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Topic;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Response;
use App\Models\User;

class TopicsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request , Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->paginate(20); //withOrder是本地作用域, 调用了topic.php中的scopeWithOrder

        //获取活跃用户
        $active_users = $user->getActiveUsers();

        return view('topics.index', compact('topics', 'active_users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('categories', 'topic'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::user()->id;
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic, Request $request)
    {
        if (!empty($topic->slug) && $topic->slug != $request->slug) {

            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);

        $categories = Category::all();

        return view('topics.create_and_edit', compact('categories', 'topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());

        return redirect()->to($topic->link(['s'=>'a']))->with('success', '更新成功！');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return redirect()->route('topics.index')->with('success', '删除成功');
    }

    /**
     * 上传图片
     */
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        $data = [

            'success'   => false,
            'msg'       => '上传失败',
            'file_path' => ''
        ];

        //上传文件
        if ($file = $request->upload_file) {

            //保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id());

            if ($result) {

                $data['success']   = true;
                $data['msg']       = '上传成功';
                $data['file_path'] = $result['path'];
            }
        }

        return $data;
    }
}
