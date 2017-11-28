<?php

namespace App\Handlers;

use Image;

/**
 * 图像上传处理类
 * Class ImageUploadHandler
 * @package App\Handlers
 */
class ImageUploadHandler
{
    //只允许下列后缀名的图片文件上传
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * 保存图片
     * @param $file
     * @param $folder
     * @param $file_prefix
     * @param $max_width 是否裁剪
     */
    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高
        $folder_name = "uploads/images/$folder/" . date("Ym", time()) . "/" . date("d", time()) . "/";

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        $upload_path = public_path() . '/' . $folder_name;

        //后缀名
        $extention = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $file_prefix . "_" . time() . "_" . str_random(10) . '.' . $extention;

        if (!in_array($extention, $this->allowed_ext)) {

            return false;
        }

        //上传
        $file->move($upload_path, $filename);

        //如果限制了宽度就进行裁剪
        if ($max_width && $extention != 'gif') {

            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [

            'path' => config('app.url') . "/$folder_name/$filename",
        ];

    }

    /**
     * 图片裁剪
     * @param $file_path
     * @param $max_width
     */
    private function reduceSize($file_path, $max_width)
    {
        //实例化
        $image = Image::make($file_path);

        $image->resize($max_width, null, function($constraint){

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        //保存
        $image->save();
    }
}