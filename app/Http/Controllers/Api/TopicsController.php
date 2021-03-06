<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\TopicRequest;

class TopicsController extends Controller
{
    /**
     * 话题列表
     * @param Request $request
     * @param Topic   $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($categoryId = $request->category_id) {

            $query->where('category_id', $categoryId);
        }

        switch ($request->order)
        {
            case 'recent' :
                $query->recent();
                break;
            default :
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 某用户发布的话题
     * @param User    $user
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex(User $user, Request $request)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 话题详情
     * @param Topic   $topic
     * @param Request $request
     */
    public function show(Topic $topic, Request $request)
    {
        return $this->response->item($topic, new TopicTransformer());
    }
    /**
     * 发表话题
     * @param TopicRequest $request
     * @param Topic        $topic
     * @return $this
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    /** 修改话题
     * @param TopicRequest $request
     * @param Topic        $topic
     * @return \Dingo\Api\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * 删除话题
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function destory(Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->delete();

        return $this->response->noContent();
    }
}
