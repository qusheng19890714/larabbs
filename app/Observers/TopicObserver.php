<?php
namespace App\Observers;

use App\Events\TopicCreate;
use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

use App\Jobs\TranslateSlug;

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->body    = clean($topic->body, 'user_topic_body'); //防止xss攻击

        $topic->excerpt = make_excerpt($topic->body); //写入摘要


    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }

    }

    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete(); //删除所有评论
    }
}