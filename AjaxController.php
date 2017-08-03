<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\Links;
use App\Http\Model\PostCategory;
use App\Http\Model\Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class AjaxController extends CommonController
{
    //PostCategory 排序
    public function postCategorySort()
    {
        $input = Input::all();
        $cate = PostCategory::find($input['id']);
        $cate->sort = $input['sort'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'分類排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'分類排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }
    //Links 排序
    public function linksSort()
    {
        $input = Input::all();
        $cate = Links::find($input['id']);
        $cate->sort = $input['sort'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //Setting 排序
    public function settingSort()
    {
        $input = Input::all();
        $cate = Setting::find($input['id']);
        $cate->sort = $input['sort'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //刪除檔案
    public function deleteFile()
    {
        $input = Input::all();

        $re = Storage::delete($input['file_path']);

        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'刪除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'刪除失敗，請稍後重試！',
            ];
        }
        return $data;
    }
}
