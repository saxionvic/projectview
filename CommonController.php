<?php

namespace App\Http\Controllers\Admin;

use Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //圖片上傳
    public function upload()
    {
        $file = Input::file('file');

        if(Request::hasFile('file')){
            if($file->isValid()){
                $realPath = $file->getRealPath(); //臨時文件路徑

                $entension = $file->getClientOriginalExtension(); //上傳文件附檔名
                $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
                $path = $file->move(base_path().'/uploads', $newName);

                $filepath = 'uploads/'.$newName;
                return $filepath;

            }
        }else{
            return 'fail';
        }

    }

}

