<?php
namespace App\Observers;

use App\Models\Topic;

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->excerpt = make_excerpt($topic->body);
    }
}