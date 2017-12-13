<?php
namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $reply->content    = clean($reply->content, 'user_topic_body'); //防止xss攻击

    }

}