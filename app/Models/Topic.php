<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * @var array
     * user_id —— 文章的作者，我们不希望文章的作者可以被随便指派；
     * last_reply_user_id —— 最后回复的用户 ID，将有程序来维护；
     * order —— 文章排序，将会是管理员专属的功能；
     * reply_count —— 回复数量，程序维护；
     * view_count —— 查看数量，程序维护；
     */
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 帖子排序
     * @param $query
     * @param $order
     */
    public function scopeWithOrder($query, $order)
    {
        switch ($order) {

            case 'recent' :  //最近回复

                $query = $this->recent();
                break;

            default :

                $query = $this->recentReplied();
                break;
        }

        return $query->with('category', 'user');

    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
}
