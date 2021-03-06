<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;

class ImagesController extends Controller
{
    //
    public function store(ImageRequest $request, ImageUploadHandler $handler, Image $image)
    {

        //获取当前用户
        $user = $this->user();
        //设置最大宽度
        $size = $request->type == 'avatar' ? 362 : 1024;

        //裁剪&存储图片
        $result = $handler->saveTest($request->image, str_plural($request->type), $user->id, $size);

        //存储image对象
        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();
        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
