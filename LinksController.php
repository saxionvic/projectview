<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\Links;
use Illuminate\Support\Facades\Input;
use Validator;

class LinksController extends CommonController
{
    public function index()
    {
        $data = Links::orderBy('sort', 'asc')->get();

        return view('admin.links.index', compact('data'));
    }

    //get.  admin/links/create 新增分類
    public function create()
    {
        return view('admin.links.create');
    }

    //post.  admin/links 新增文章提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'name' => 'required',
        ];

        $message =[
            'name.required' => '文章標題不能為空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Links::create($input);
            if($re){
                return redirect('admin/links');
            }else{
                return back()->with('msg', '新增失敗！');
            }
        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/links/{id}/edit 編輯文章
    public function edit($id)
    {
        $field = Links::find($id);
        return view('admin.links.edit', compact('field'));
    }

    //put. admin/links/{links} 更新友情連結
    public function update($id)
    {
        $input = Input::except('_token', '_method');
        $re = Links::where('id',$id)->update($input);
        if($re){
            return redirect('admin/links');
        }else{
            return back()->with('errors','修改失敗，請稍後重試!');
        }
    }

    //get. admin/links/{links} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/links/{links} 刪除友情連結
    public function destroy($id)
    {
        $re = Links::where('id', $id)->delete();

        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }

}
