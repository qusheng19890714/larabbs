<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Redis;

trait LastActivedAtHelper
{
    //缓存相关
    protected $hash_prefix  = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        //字段名称
        $field = $this->getHashField();

        $now = Carbon::now()->toDateTimeString();

        //redis写入
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        //获取昨天的日期
        $hash = $this->getHashFromDateString(Carbon::now()->subDay()->toDateString());

        //从Redis中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);

        foreach ($dates as $user_id=>$actived_at)
        {
            $user_id = str_replace($this->field_prefix, '', $user_id);

            if ($user = $this->find($user_id)) {

                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        // 以数据库为中心的存储，既已同步，即可删除
        Redis::del($hash);
    }

    /**
     * last_actived_at 访问器
     * @param $value
     */
    public function getLastActivedAtAttribute($value)
    {
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        $field= $this->getHashField();

        //三元运算符，优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash, $field) ? : $value;

        if ($datetime) {

            return new Carbon($datetime);

        }else {

            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        return $this->field_prefix . $this->id;
    }
}
