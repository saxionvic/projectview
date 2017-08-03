<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

require_once 'public/org/code/code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
        if($input = Input::all()){
            //取得驗證碼文字
            $code = new \Code();
            $_code = $code->get();
            $status = Config::get('login.status');

            //判斷驗證碼 > 測試模式 OR 正式
            if(!$status){
                if(strtoupper($input['code'] ) !=  $_code){
                    return back()->with('msg','驗證碼輸入錯誤!');
                }
            }

            //判斷帳密
            $user = User::first();
            if($user->user_name != $input['username'] || Crypt::decrypt($user->user_pass) != $input['password']){
                return back()->with('msg','帳號或密碼輸入錯誤!');
            }

            //登入成功
            session(['user' => $user]);
            return redirect('admin/index');
        }else{

            return view('admin.login');
        }

    }

    public function logout()
    {
        Session::forget('user');
        return redirect('admin/login')->with('msg', '登出成功');
    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    public function crypt()
    {
        //類似md5，laravel的加密方法
        $str = '123456';

        echo Crypt::encrypt($str);
    }
}
