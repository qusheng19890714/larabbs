<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    public $cache_key = 'larabbs_links';
    protected $cache_expire_in_minutes = 1440;

    public function getAllCached()
    {
        //先取缓存数据,没有则读取数据库,并存入缓存

        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function (){

            return $this->all();

        });
    }
}
