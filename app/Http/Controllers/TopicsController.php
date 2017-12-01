<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Topic;
use Auth;
use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Response;

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
    public function index(Request $request , Topic $topic)
    {

        $topics = $topic->withOrder($request->order)->paginate(20); //withOrder是本地作用域, 调用了topic.php中的scopeWithOrder

        return view('topics.index', compact('topics'));
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

        return redirect()->route('topics.show', $topic->id)->with('success', '成功创建话题');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
