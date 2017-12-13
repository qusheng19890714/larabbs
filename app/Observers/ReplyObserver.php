<?php
namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content    = clean($reply->content, 'user_topic_body'); //防止xss攻击

    }

    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1); //回复数量加1
    }

}